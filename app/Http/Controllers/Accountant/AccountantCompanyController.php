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
            
        // Get folders to display - if there's a company-named root folder, show its children
        // Otherwise show all root folders
        $companyRootFolder = $company->folders()
            ->whereNull('parent_id')
            ->where('name', $company->name)
            ->first();
            
        if ($companyRootFolder) {
            // Show the subfolders of the company-named folder
            $folders = $companyRootFolder->children;
        } else {
            // Show all root folders if no company-named folder exists
            $folders = $company->folders()
                ->whereNull('parent_id')
                ->get();
        }
        
        return view('accountant.companies.show', compact('company', 'folders'));
    }

    /**
     * Display the company's folder contents.
     *
     * @param  int  $companyId
     * @param  int  $folderId
     * @return \Illuminate\Http\Response
     */
    public function viewFolder($companyId, $folderId)
    {
        $accountant = Auth::user();
        $company = $accountant->assignedCompanies()
            ->with(['user', 'country'])
            ->findOrFail($companyId);
            
        // Check if folder belongs to the company
        $folder = $company->folders()
            ->with(['files', 'children', 'parent'])
            ->findOrFail($folderId);
        
        // Get breadcrumb path
        $breadcrumbs = [
            ['title' => __('Home'), 'href' => route('accountant.dashboard'), 'first' => true],
            ['title' => __('Companies'), 'href' => route('accountant.companies.index')],
            ['title' => $company->name, 'href' => route('accountant.companies.show', $company)],
        ];
        
        // Build folder breadcrumb path (excluding company level)
        $folderPath = [];
        $currentFolder = $folder;
        while ($currentFolder) {
            array_unshift($folderPath, $currentFolder);
            $currentFolder = $currentFolder->parent;
        }
        
        // Add folder breadcrumbs (company → folder1 → folder2 → current)
        // Skip if first folder has same name as company to avoid duplication
        foreach ($folderPath as $index => $pathFolder) {
            // Skip if this is the first folder and it has the same name as the company
            if ($index === 0 && $pathFolder->name === $company->name) {
                continue;
            }
            
            if ($index === count($folderPath) - 1) {
                // Last folder (current folder) - no link
                $breadcrumbs[] = ['title' => $pathFolder->name];
            } else {
                // Parent folders - add links
                $breadcrumbs[] = [
                    'title' => $pathFolder->name,
                    'href' => route('accountant.companies.folders.show', ['company' => $company->id, 'folder' => $pathFolder->id])
                ];
            }
        }
        
        return view('accountant.companies.folder', compact('company', 'folder', 'breadcrumbs'));
    }
}
