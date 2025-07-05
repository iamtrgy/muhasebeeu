<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    /**
     * Display a listing of all companies.
     */
    public function index()
    {
        $companies = Company::with(['user', 'country'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        
        return view('admin.companies.create', compact('users', 'countries'));
    }

    /**
     * Store a newly created company in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'user_id' => 'required|exists:users,id',
            'country_id' => 'required|exists:countries,id',
            'tax_number' => 'nullable|string|max:255|unique:companies,tax_number',
            'vat_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'foundation_date' => 'nullable|date',
        ]);

        $company = Company::create($request->all());

        return redirect()->route('admin.companies.show', $company)
            ->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified company details.
     */
    public function show(Company $company)
    {
        $company->load(['user', 'country']);
        return view('admin.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company)
    {
        $users = User::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        
        return view('admin.companies.edit', compact('company', 'users', 'countries'));
    }

    /**
     * Update the specified company in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'user_id' => 'required|exists:users,id',
            'country_id' => 'required|exists:countries,id',
            'tax_number' => 'nullable|string|max:255|unique:companies,tax_number,' . $company->id,
            'vat_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'foundation_date' => 'nullable|date',
        ]);

        $company->update($request->all());

        return redirect()->route('admin.companies.show', $company)
            ->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified company from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    /**
     * Display a list of duplicate companies based on name or tax number.
     */
    public function findDuplicates()
    {
        // Find companies with duplicate names
        $duplicateNames = Company::select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('name');

        // Find companies with duplicate tax numbers (registry codes)
        $duplicateTaxNumbers = Company::select('tax_number')
            ->whereNotNull('tax_number')
            ->groupBy('tax_number')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('tax_number');

        // Get companies with duplicate names
        $companiesWithDuplicateNames = [];
        foreach ($duplicateNames as $name) {
            $companiesWithDuplicateNames[$name] = Company::where('name', $name)
                ->with('user', 'country')
                ->get();
        }

        // Get companies with duplicate tax numbers
        $companiesWithDuplicateTaxNumbers = [];
        foreach ($duplicateTaxNumbers as $taxNumber) {
            $companiesWithDuplicateTaxNumbers[$taxNumber] = Company::where('tax_number', $taxNumber)
                ->with('user', 'country')
                ->get();
        }

        return view('admin.companies.duplicates', compact(
            'companiesWithDuplicateNames',
            'companiesWithDuplicateTaxNumbers'
        ));
    }

    /**
     * Merge duplicate companies into one and delete the others.
     */
    public function mergeDuplicates(Request $request)
    {
        $request->validate([
            'primary_company_id' => 'required|exists:companies,id',
            'duplicate_company_ids' => 'required|array',
            'duplicate_company_ids.*' => 'exists:companies,id',
        ]);

        $primaryCompanyId = $request->primary_company_id;
        $duplicateCompanyIds = $request->duplicate_company_ids;

        // Make sure primary company is not in the duplicates list
        $duplicateCompanyIds = array_filter($duplicateCompanyIds, function($id) use ($primaryCompanyId) {
            return $id != $primaryCompanyId;
        });

        if (empty($duplicateCompanyIds)) {
            return redirect()->back()->with('error', 'No duplicate companies selected for merging.');
        }

        // TODO: Implement logic to merge related records (folders, files, etc.) 
        // from duplicate companies to primary company if needed

        // Delete the duplicate companies
        Company::whereIn('id', $duplicateCompanyIds)->delete();

        return redirect()->route('admin.companies.duplicates')
            ->with('success', 'Duplicate companies have been merged successfully.');
    }

    /**
     * Show the form for assigning accountants to a company.
     */
    public function assignAccountants(Company $company)
    {
        // Get all accountant users
        $availableAccountants = \App\Models\User::where('is_accountant', true)
            ->orderBy('name')
            ->get();
        
        // Get currently assigned accountants
        $assignedAccountants = $company->accountants()->pluck('users.id')->toArray();
        
        return view('admin.companies.assign-accountants', compact(
            'company',
            'availableAccountants',
            'assignedAccountants'
        ));
    }

    /**
     * Update the accountants assigned to a company.
     */
    public function updateAccountants(Request $request, Company $company)
    {
        // Validate the request
        $request->validate([
            'assigned_accountants' => 'nullable|array',
            'assigned_accountants.*' => 'exists:users,id',
        ]);
        
        // Sync the accountants to the company
        $company->accountants()->sync($request->assigned_accountants ?? []);
        
        // Also sync the accountants to the company's owner
        if (!empty($request->assigned_accountants)) {
            foreach ($request->assigned_accountants as $accountantId) {
                // Add the company's owner to this accountant's assigned users
                $accountant = \App\Models\User::find($accountantId);
                $currentUsers = $accountant->assignedUsers()->pluck('user_id')->toArray();
                if (!in_array($company->user_id, $currentUsers)) {
                    $accountant->assignedUsers()->attach($company->user_id);
                }
            }
        }
        
        return redirect()->route('admin.companies.show', $company)
            ->with('success', 'Accountants assigned successfully.');
    }
} 