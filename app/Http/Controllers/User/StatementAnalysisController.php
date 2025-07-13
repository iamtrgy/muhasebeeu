<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\BankTransaction;
use App\Models\Invoice;
use App\Services\BankStatementAnalyzerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatementAnalysisController extends Controller
{
    protected $analyzerService;

    public function __construct(BankStatementAnalyzerService $analyzerService)
    {
        $this->analyzerService = $analyzerService;
    }

    /**
     * Analyze a bank statement
     */
    public function analyze(Request $request, File $file)
    {
        // Load folder relationship if not loaded
        $file->load('folder');
        
        // Verify user has access to this file
        if (!$this->userCanAccessFile($file)) {
            abort(403, 'Unauthorized access to file');
        }

        // Check if file is in Banks folder
        if (!$this->isInBanksFolder($file)) {
            return response()->json([
                'success' => false,
                'message' => 'This file is not a bank statement',
                'debug' => [
                    'file_id' => $file->id,
                    'folder_id' => $file->folder_id,
                    'folder_path' => $file->folder ? $file->folder->full_path : null,
                    'folder_name' => $file->folder ? $file->folder->name : null
                ]
            ], 422);
        }

        // Get force parameter from request
        $force = $request->input('force', false);

        // Analyze the statement
        $result = $this->analyzerService->analyzeStatement($file, $force);

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->route('user.banks.statements.show', $file)
                ->with('success', 'Statement analyzed successfully. Found ' . $result['transaction_count'] . ' transactions.');
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Show statement details with transactions
     */
    public function show(File $file)
    {
        // Verify user has access
        if (!$this->userCanAccessFile($file)) {
            abort(403, 'Unauthorized access to file');
        }

        // Check if analyzed
        if (!$file->statement_analyzed) {
            return redirect()->route('user.banks.index')
                ->with('info', 'This statement has not been analyzed yet.');
        }

        // Get transactions with relationships
        $transactions = $file->bankTransactions()
            ->with('matchedInvoice')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Calculate summary data
        $summary = [
            'total_debits' => $transactions->where('type', 'debit')->sum('amount'),
            'total_credits' => $transactions->where('type', 'credit')->sum('amount'),
            'transaction_count' => $transactions->count(),
            'matched_count' => $transactions->whereNotNull('matched_invoice_id')->count(),
            'unmatched_count' => $transactions->whereNull('matched_invoice_id')->count(),
            'currency' => $transactions->first()->currency ?? 'EUR',
        ];

        // Get category breakdown
        $categoryBreakdown = $transactions->groupBy('category')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('amount'),
                    'type' => $group->first()->type
                ];
            })
            ->sortByDesc('total');

        return view('user.banks.statements.show', [
            'file' => $file,
            'transactions' => $transactions,
            'summary' => $summary,
            'categoryBreakdown' => $categoryBreakdown,
        ]);
    }

    /**
     * Update a transaction (edit notes, category, etc.)
     */
    public function updateTransaction(Request $request, BankTransaction $transaction)
    {
        // Verify user has access
        if (!$this->userCanAccessTransaction($transaction)) {
            abort(403, 'Unauthorized access to transaction');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100',
        ]);

        $transaction->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'transaction' => $transaction
            ]);
        }

        return back()->with('success', 'Transaction updated successfully');
    }

    /**
     * Match a transaction to an invoice
     */
    public function matchInvoice(Request $request, BankTransaction $transaction)
    {
        // Verify user has access
        if (!$this->userCanAccessTransaction($transaction)) {
            abort(403, 'Unauthorized access to transaction');
        }

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'confidence' => 'nullable|integer|min:0|max:100'
        ]);

        // Verify invoice belongs to same company
        $invoice = Invoice::findOrFail($validated['invoice_id']);
        if ($invoice->company_id !== $transaction->company_id) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice does not belong to the same company'
            ], 422);
        }

        // Update transaction
        $transaction->update([
            'matched_invoice_id' => $validated['invoice_id'],
            'match_confidence' => $validated['confidence'] ?? 100,
            'match_status' => 'manual_matched'
        ]);

        // Update invoice status if needed
        if ($invoice->status === 'sent' || $invoice->status === 'overdue') {
            $invoice->update(['status' => 'paid']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'transaction' => $transaction->load('matchedInvoice')
            ]);
        }

        return back()->with('success', 'Transaction matched to invoice successfully');
    }

    /**
     * Remove invoice match from transaction
     */
    public function unmatchInvoice(Request $request, BankTransaction $transaction)
    {
        // Verify user has access
        if (!$this->userCanAccessTransaction($transaction)) {
            abort(403, 'Unauthorized access to transaction');
        }

        $transaction->update([
            'matched_invoice_id' => null,
            'match_confidence' => null,
            'match_status' => 'unmatched'
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'transaction' => $transaction
            ]);
        }

        return back()->with('success', 'Invoice match removed');
    }

    /**
     * Get potential invoice matches for a transaction
     */
    public function findMatches(Request $request, BankTransaction $transaction)
    {
        // Verify user has access
        if (!$this->userCanAccessTransaction($transaction)) {
            abort(403, 'Unauthorized access to transaction');
        }

        // Find potential matches
        $query = Invoice::where('company_id', $transaction->company_id)
            ->where('currency', $transaction->currency);

        // For credits (payments received), look at income invoices
        // For debits (payments made), look at expense invoices
        if ($transaction->type === 'credit') {
            // Income - look for unpaid invoices we sent
            $query->whereIn('status', ['sent', 'overdue', 'partial']);
        }

        // Date range - invoice should be before or near transaction date
        $query->where('invoice_date', '<=', $transaction->transaction_date)
              ->where('invoice_date', '>=', $transaction->transaction_date->subDays(90));

        // Amount matching with tolerance (±1% for rounding)
        $tolerance = $transaction->amount * 0.01;
        $query->whereBetween('total', [
            $transaction->amount - $tolerance,
            $transaction->amount + $tolerance
        ]);

        $potentialMatches = $query->orderBy('invoice_date', 'desc')->get();

        // Calculate match confidence for each
        $matches = $potentialMatches->map(function ($invoice) use ($transaction) {
            $confidence = 0;
            
            // Exact amount match
            if ($invoice->total == $transaction->amount) {
                $confidence += 50;
            } else {
                // Partial score based on closeness
                $diff = abs($invoice->total - $transaction->amount);
                $confidence += max(0, 40 - ($diff / $transaction->amount * 100));
            }
            
            // Date proximity (max 30 points)
            $daysDiff = $invoice->invoice_date->diffInDays($transaction->transaction_date);
            $confidence += max(0, 30 - $daysDiff);
            
            // Reference number match (20 points)
            if ($transaction->reference_number && 
                (strpos($transaction->description, $invoice->invoice_number) !== false ||
                 strpos($invoice->invoice_number, $transaction->reference_number) !== false)) {
                $confidence += 20;
            }
            
            $invoice->match_confidence = min(100, round($confidence));
            return $invoice;
        })->sortByDesc('match_confidence');

        return response()->json([
            'success' => true,
            'matches' => $matches
        ]);
    }

    /**
     * Check if user can access file
     */
    protected function userCanAccessFile(File $file)
    {
        $user = Auth::user();
        return $file->folder && $file->folder->isAccessibleBy($user);
    }

    /**
     * Check if user can access transaction
     */
    protected function userCanAccessTransaction(BankTransaction $transaction)
    {
        $user = Auth::user();
        return $user->companies->contains($transaction->company_id);
    }

    /**
     * Check if file is in Banks folder structure
     */
    protected function isInBanksFolder(File $file)
    {
        if (!$file->folder) {
            \Log::warning('File has no folder', ['file_id' => $file->id]);
            return false;
        }

        // Load parent relationships to ensure full_path works correctly
        $file->folder->load('parent.parent.parent');

        // Check if the folder path contains "Banks"
        $fullPath = $file->folder->full_path;
        $isInBanks = strpos($fullPath, '/Banks/') !== false || 
                     strpos($fullPath, 'Banks/') !== false ||
                     $file->folder->name === 'Banks';
                     
        \Log::info('Checking if file is in Banks folder', [
            'file_id' => $file->id,
            'folder_path' => $fullPath,
            'folder_name' => $file->folder->name,
            'is_in_banks' => $isInBanks
        ]);
        
        return $isInBanks;
    }
}