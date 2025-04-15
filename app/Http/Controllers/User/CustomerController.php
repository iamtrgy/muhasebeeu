<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userclients = Customer::where('user_id', auth()->id())->orderBy('name')->paginate(10);
        
        return view('user.customers.index', compact('userclients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.customers.create');
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
            'tax_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ]);

        $customer = Customer::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'tax_number' => $request->tax_number,
            'address' => $request->address,
        ]);

        return redirect()->route('user.customers.index')
            ->with('success', 'Customer added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // Check if the customer belongs to the authenticated user
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('user.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        // Check if the customer belongs to the authenticated user
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('user.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // Check if the customer belongs to the authenticated user
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'tax_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'tax_number' => $request->tax_number,
            'address' => $request->address,
        ]);

        return redirect()->route('user.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Check if the customer belongs to the authenticated user
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if customer has invoices
        if ($customer->invoices()->count() > 0) {
            return redirect()->route('user.customers.index')
                ->with('error', 'This customer cannot be deleted because it has associated invoices.');
        }
        
        $customer->delete();

        return redirect()->route('user.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
