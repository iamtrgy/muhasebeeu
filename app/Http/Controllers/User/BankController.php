<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\File;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BankController extends Controller
{
    /**
     * Display banks main page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get active company
        $company = $user->activeCompany ?? $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('user.companies.index')
                ->with('error', 'Please create or select a company first.');
        }
        
        // Find the Banks folder for this company
        $rootFolder = Folder::where('name', $company->name)
            ->where('company_id', $company->id)
            ->where('parent_id', null)
            ->first();
            
        if (!$rootFolder) {
            return redirect()->route('user.folders.index')
                ->with('error', 'Company folder structure not found. Please contact support.');
        }
        
        $banksFolder = Folder::where('name', 'Banks')
            ->where('parent_id', $rootFolder->id)
            ->where('company_id', $company->id)
            ->with(['children.children']) // Load years and months
            ->first();
            
        if (!$banksFolder) {
            // Create Banks folder if it doesn't exist (for old companies)
            $folderService = app(\App\Services\FolderStructureService::class);
            $folderService->createMainCategoryFolders($user, $rootFolder, $company);
            
            return redirect()->route('user.banks.index')
                ->with('info', 'Banks folder structure has been created.');
        }
        
        // Get selected year and month from request
        $selectedYear = $request->get('year', Carbon::now()->year);
        $selectedMonth = $request->get('month', Carbon::now()->month);
        
        // Get all years available
        $years = $banksFolder->children()
            ->orderBy('name', 'desc')
            ->get();
            
        // Find selected year folder
        $yearFolder = $years->firstWhere('name', $selectedYear);
        
        // Get months for selected year
        $months = [];
        $selectedMonthFolder = null;
        $files = collect();
        $monthlyBalance = 0;
        
        if ($yearFolder) {
            $months = $yearFolder->children()
                ->orderBy('created_at', 'asc')
                ->get();
                
            // Find selected month folder
            $monthName = Carbon::createFromDate(null, (int) $selectedMonth, 1)->format('F');
            $selectedMonthFolder = $months->firstWhere('name', $monthName);
            
            if ($selectedMonthFolder) {
                // Get files for selected month
                $files = $selectedMonthFolder->files()
                    ->with('uploader')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                // Calculate monthly balance (could be extracted from files or metadata)
                // For now, just count files
                $monthlyBalance = $files->count();
            }
        }
        
        // Get recent bank statements across all months
        $recentStatements = File::whereHas('folder', function ($query) use ($banksFolder) {
                $query->where('path', 'like', $banksFolder->path . '%');
            })
            ->with(['folder', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Debug logging for bank statements
        \Log::info('Banks page debug', [
            'selected_year' => $selectedYear,
            'selected_month' => $selectedMonth,
            'month_name' => $monthName ?? 'not_set',
            'year_folder_exists' => $yearFolder ? true : false,
            'selected_month_folder_exists' => $selectedMonthFolder ? true : false,
            'selected_month_folder_path' => $selectedMonthFolder ? $selectedMonthFolder->full_path : 'null',
            'files_count' => $files->count(),
            'files_in_folder' => $files->pluck('original_name')->toArray()
        ]);

        return view('user.banks.index', [
            'company' => $company,
            'banksFolder' => $banksFolder,
            'years' => $years,
            'months' => $months,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'selectedMonthFolder' => $selectedMonthFolder,
            'files' => $files,
            'monthlyBalance' => $monthlyBalance,
            'recentStatements' => $recentStatements,
        ]);
    }
    
    /**
     * Show upload form for bank statements
     */
    public function create(Request $request)
    {
        // Get folder ID from request
        $folderId = $request->get('folder_id');
        
        if (!$folderId) {
            return redirect()->route('user.banks.index')
                ->with('error', 'Please select a month to upload bank statement.');
        }
        
        $folder = Folder::findOrFail($folderId);
        
        // Verify user has access
        if (!$folder->isAccessibleBy(auth()->user())) {
            abort(403, 'Unauthorized access to folder.');
        }
        
        return view('user.banks.upload', [
            'folder' => $folder
        ]);
    }
}