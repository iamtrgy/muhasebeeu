<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class AccountantCompanyController extends Controller
{

    /**
     * Display a listing of companies assigned to the accountant.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountant = Auth::user();
        $companies = $accountant->assignedCompanies()
            ->with(['user', 'country'])
            ->paginate(10);
        
        return view('accountant.companies.index', compact('companies'));
    }

    /**
     * Display the specified company's details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accountant = Auth::user();
        $company = $accountant->assignedCompanies()
            ->with(['user', 'country'])
            ->findOrFail($id);
            
        // Get only the folders directly associated with this company
        $folders = $company->folders()
            ->whereNull('parent_id') // Still only show root folders in this view
            ->get();
        
        return view('accountant.companies.show', compact('company', 'folders'));
    }
}
