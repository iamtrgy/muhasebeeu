<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserClient;
use Illuminate\Http\Request;

class UserClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = UserClient::where('user_id', auth()->id())->orderBy('name')->paginate(10);
        
        return view('user.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'vat_number' => 'nullable|string|max:50',
            'company_reg_number' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:2',
            'address' => 'nullable|string|max:1000',
        ]);

        $client = UserClient::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vat_number' => $request->vat_number,
            'company_reg_number' => $request->company_reg_number,
            'country' => $request->country,
            'address' => $request->address,
        ]);

        return redirect()->route('user.clients.index')
            ->with('success', 'Client added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserClient $client)
    {
        // Check if the client belongs to the authenticated user
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('user.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserClient $client)
    {
        // Check if the client belongs to the authenticated user
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('user.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserClient $client)
    {
        // Check if the client belongs to the authenticated user
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'vat_number' => 'nullable|string|max:50',
            'company_reg_number' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:2',
            'address' => 'nullable|string|max:1000',
        ]);

        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vat_number' => $request->vat_number,
            'company_reg_number' => $request->company_reg_number,
            'country' => $request->country,
            'address' => $request->address,
        ]);

        return redirect()->route('user.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserClient $client)
    {
        // Check if the client belongs to the authenticated user
        if ($client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if client has invoices
        if ($client->invoices()->count() > 0) {
            return redirect()->route('user.clients.index')
                ->with('error', 'This client cannot be deleted because it has associated invoices.');
        }
        
        $client->delete();

        return redirect()->route('user.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
