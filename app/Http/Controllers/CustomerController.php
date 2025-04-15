<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:30',
            'company_reg_number' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:2',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vat_number' => $request->vat_number,
            'company_reg_number' => $request->company_reg_number,
            'country' => $request->country,
            'address' => $request->address,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('user.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:30',
            'company_reg_number' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:2',
            'address' => 'nullable|string',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vat_number' => $request->vat_number,
            'company_reg_number' => $request->company_reg_number,
            'country' => $request->country,
            'address' => $request->address,
        ]);

        return redirect()->route('user.customers.index')
            ->with('success', 'Customer updated successfully.');
    }
} 