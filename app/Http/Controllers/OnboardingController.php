<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\FolderStructureService;
use App\Services\EstonianCompanyService;

class OnboardingController extends Controller
{
    /**
     * Folder structure service instance.
     */
    protected $folderService;
    
    /**
     * Estonian company service instance.
     */
    protected $estonianCompanyService;

    /**
     * Constructor to inject dependencies
     */
    public function __construct(
        FolderStructureService $folderService,
        EstonianCompanyService $estonianCompanyService
    ) {
        $this->folderService = $folderService;
        $this->estonianCompanyService = $estonianCompanyService;
    }

    /**
     * Show the onboarding index or redirect to the appropriate step
     */
    public function index()
    {
        $user = Auth::user();

        // If onboarding is completed and user has at least one company, redirect to dashboard
        if ($user->onboarding_completed && $user->companies()->count() > 0) {
            return redirect()->route('user.dashboard');
        }

        // If onboarding is marked as completed but user has no companies,
        // reset onboarding status and redirect to company creation
        if ($user->onboarding_completed && $user->companies()->count() === 0) {
            $user->update([
                'onboarding_completed' => false,
                'onboarding_step' => 'company_creation'
            ]);
            return redirect()->route('onboarding.step2');
        }

        // Redirect to the appropriate step based on the user's onboarding status
        switch ($user->onboarding_step) {
            case 'country_selection':
                return redirect()->route('onboarding.step1');
            case 'company_creation':
                return redirect()->route('onboarding.step2');
            default:
                // Start with country selection if no step is set
                $user->update(['onboarding_step' => 'country_selection']);
                return redirect()->route('onboarding.step1');
        }
    }

    /**
     * Show the country selection step
     */
    public function showCountryStep()
    {
        $user = Auth::user();

        // If onboarding is already completed, redirect to dashboard
        if ($user->onboarding_completed) {
            return redirect()->route('user.dashboard');
        }

        // Get all countries
        $countries = Country::orderBy('name')->get();

        return view('onboarding.country', compact('countries'));
    }

    /**
     * Process the country selection
     */
    public function processCountryStep(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $user = Auth::user();

        // Update the user's country and onboarding step
        $user->update([
            'country_id' => $request->country_id,
            'onboarding_step' => 'company_creation',
        ]);

        return redirect()->route('onboarding.step2');
    }

    /**
     * Show the company creation step
     */
    public function showCompanyStep()
    {
        $user = Auth::user();

        // If onboarding is already completed, redirect to dashboard
        if ($user->onboarding_completed) {
            return redirect()->route('user.dashboard');
        }

        // If country is not selected, redirect to country step
        if (!$user->country_id) {
            return redirect()->route('onboarding.country');
        }

        return view('onboarding.company', [
            'country' => $user->country,
        ]);
    }

    /**
     * Process the company creation or selection
     */
    public function processCompanyStep(Request $request)
    {
        $user = Auth::user();

        // Validate based on the option selected
        if ($request->option === 'create') {
            $request->validate([
                'name' => 'required|string|max:255|unique:companies,name',
                'tax_number' => 'nullable|string|max:50|unique:companies,tax_number',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'foundation_date' => 'nullable|date',
            ]);

            // Create the company
            $company = Company::create([
                'name' => $request->name,
                'country_id' => $user->country_id,
                'user_id' => $user->id,
                'tax_number' => $request->tax_number,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'is_own_company' => true,
                'foundation_date' => $request->foundation_date,
            ]);

            // Attach company to user with owner role
            $user->companies()->attach($company->id, ['role' => 'owner']);
        } elseif ($request->option === 'existing') {
            // Check if it's Estonia
            $isEstonia = $user->country && $user->country->code === 'EE';
            
            if ($isEstonia) {
                $request->validate([
                    'company_name' => 'required|string|max:255',
                    'company_registry_code' => 'required|string|max:50',
                    'company_address' => 'nullable|string|max:255',
                    'company_vat_number' => 'nullable|string|max:100',
                    'company_foundation_date' => 'required|date',
                ]);
                
                // Create a record for the existing Estonian company
                $company = Company::create([
                    'name' => $request->company_name,
                    'country_id' => $user->country_id,
                    'user_id' => $user->id,
                    'tax_number' => $request->company_registry_code,
                    'vat_number' => $request->company_vat_number,
                    'address' => $request->company_address,
                    'is_own_company' => false,
                    'foundation_date' => $request->company_foundation_date,
                ]);
            } else {
                $request->validate([
                    'company_name' => 'required|string|max:255',
                    'company_address' => 'nullable|string|max:255',
                    'company_phone' => 'nullable|string|max:20',
                    'company_email' => 'nullable|email|max:255',
                    'company_vat_number' => 'nullable|string|max:100',
                    'company_foundation_date' => 'nullable|date',
                ]);
                
                // Create a record for the existing company
                $company = Company::create([
                    'name' => $request->company_name,
                    'country_id' => $user->country_id,
                    'user_id' => $user->id,
                    'address' => $request->company_address,
                    'phone' => $request->company_phone,
                    'email' => $request->company_email,
                    'vat_number' => $request->company_vat_number,
                    'foundation_date' => $request->company_foundation_date,
                    'is_own_company' => false,
                ]);
            }

            // Attach company to user with owner role
            $user->companies()->attach($company->id, ['role' => 'owner']);
        } else {
            return back()->withErrors(['option' => 'Please select a valid option.']);
        }

        // Set as current company and mark onboarding as completed
        $user->update([
            'current_company_id' => $company->id,
            'onboarding_completed' => true,
            'onboarding_step' => 'completed'
        ]);

        // Create folder structure for the company
        $this->folderService->createCompanyFolders($user, $company);

        // Redirect to subscription plans
        return redirect()->route('user.subscription.plans')
            ->with('success', 'Company setup completed! Please select a subscription plan to continue.');
    }

    /**
     * Skip onboarding (for testing purposes)
     */
    public function skip()
    {
        $user = Auth::user();
        
        $user->update([
            'onboarding_completed' => true,
            'onboarding_step' => 'completed',
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Onboarding skipped.');
    }

    public function storeCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_tax_number' => 'required|string|max:255',
            'company_vat_number' => 'nullable|string|max:255',
            'company_foundation_date' => 'nullable|date',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $user = auth()->user();

        if ($validated['company_id']) {
            // Use existing company
            $company = Company::find($validated['company_id']);
            $company->update([
                'vat_number' => $validated['company_vat_number'],
                'foundation_date' => $validated['company_foundation_date'],
            ]);
        } else {
            // Create new company
            $company = Company::create([
                'name' => $validated['company_name'],
                'tax_number' => $validated['company_tax_number'],
                'vat_number' => $validated['company_vat_number'],
                'foundation_date' => $validated['company_foundation_date'],
                'country' => 'EE',
            ]);
        }

        // Attach company to user
        $user->companies()->attach($company->id, ['role' => 'owner']);

        // Set as current company
        $user->update(['current_company_id' => $company->id]);

        return redirect()->route('onboarding.step3');
    }
}
