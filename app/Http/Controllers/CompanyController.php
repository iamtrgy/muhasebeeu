<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the user's companies.
     */
    public function index()
    {
        $user = Auth::user();
        $companies = Company::where('user_id', $user->id)
                          ->orderBy('name')
                          ->get();

        if ($companies->isEmpty()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'No company information found.');
        }

        return view('user.companies.index', compact('companies'));
    }

    /**
     * Display the company details for the specified company.
     */
    public function show($id = null)
    {
        $user = Auth::user();
        
        if ($id === null) {
            return redirect()->route('user.companies.index');
        }
        
        $company = Company::where('id', $id)
                        ->where('user_id', $user->id)
                        ->first();

        if (!$company) {
            return redirect()->route('user.companies.index')
                ->with('error', 'Company not found or you do not have permission to view it.');
        }

        return view('user.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the company details.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $company = Company::where('id', $id)
                        ->where('user_id', $user->id)
                        ->first();

        if (!$company) {
            return redirect()->route('user.companies.index')
                ->with('error', 'Company not found or you do not have permission to edit it.');
        }

        $countries = Country::orderBy('name')->get();
        return view('user.companies.edit', compact('company', 'countries'));
    }

    /**
     * Update the company details.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $company = Company::where('id', $id)
                        ->where('user_id', $user->id)
                        ->first();

        if (!$company) {
            return redirect()->route('user.companies.index')
                ->with('error', 'Company not found or you do not have permission to update it.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'tax_number' => 'nullable|string|max:50|unique:companies,tax_number,' . $company->id,
            'vat_number' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'foundation_date' => 'nullable|date',
        ]);

        $company->update([
            'name' => $request->name,
            'tax_number' => $request->tax_number,
            'vat_number' => $request->vat_number,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'foundation_date' => $request->foundation_date,
        ]);

        return redirect()->route('company.show', $company->id)
            ->with('success', 'Company information updated successfully.');
    }
} 