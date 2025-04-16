<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FolderController as AdminFolderController;
use App\Http\Controllers\Admin\FileController as AdminFileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckOnboardingStatus;
use App\Http\Middleware\RedirectAdminToDashboard;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCompanyController;
use App\Http\Controllers\Accountant\AccountantDashboardController;
use App\Http\Controllers\Accountant\AccountantUserController;
use App\Http\Controllers\Accountant\AccountantCompanyController;
use App\Http\Controllers\Accountant\AccountantFolderController;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->is_accountant) {
            return redirect()->route('accountant.dashboard');
        }
        // Check if onboarding is completed
        if (auth()->user()->onboarding_completed) {
            return redirect()->route('user.dashboard'); 
        } else {
            return redirect()->route('onboarding.step1');
        }
    } else {
        return view('welcome');
    }
})->name('home');

// Onboarding Routes
Route::middleware('auth')->group(function () {
    Route::get('/onboarding/step1', [OnboardingController::class, 'showCountryStep'])->name('onboarding.step1');
    Route::post('/onboarding/step1', [OnboardingController::class, 'processCountryStep'])->name('onboarding.postStep1');
    Route::get('/onboarding/step2', [OnboardingController::class, 'showCompanyStep'])->name('onboarding.step2');
    Route::post('/onboarding/step2', [OnboardingController::class, 'processCompanyStep'])->name('onboarding.postStep2');
    Route::get('/onboarding/step3', [OnboardingController::class, 'step3'])->name('onboarding.step3');
    Route::post('/onboarding/step3', [OnboardingController::class, 'postStep3'])->name('onboarding.postStep3');
    Route::get('/onboarding/step4', [OnboardingController::class, 'step4'])->name('onboarding.step4');
    Route::post('/onboarding/step4', [OnboardingController::class, 'postStep4'])->name('onboarding.postStep4');
    Route::get('/onboarding/step5', [OnboardingController::class, 'step5'])->name('onboarding.step5');
    Route::post('/onboarding/step5', [OnboardingController::class, 'postStep5'])->name('onboarding.postStep5');
    Route::get('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');
});

// All routes that require subscription - MUST COME BEFORE UNPROTECTED ROUTES
Route::middleware(['auth', 'verified', 'subscribed', 'onboarding.complete'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/current-month-folder', [FolderController::class, 'currentMonthFolder'])->name('current-month-folder');

        // Subscription management (requires active subscription)
        Route::get('/subscription/{plan}/payment', [SubscriptionController::class, 'showPaymentForm'])->name('subscription.payment.form');
        Route::post('/subscription/create', [SubscriptionController::class, 'subscribe'])->name('subscription.create');
        Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
        Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
        Route::get('/billing-portal', [SubscriptionController::class, 'billingPortal'])->name('subscription.billing.portal');
        
        // Clients Management
        Route::resource('clients', \App\Http\Controllers\User\UserClientController::class);
        
        // Customers Management
        Route::resource('userclients', \App\Http\Controllers\User\CustomerController::class);
        
        // Folders Management
        Route::resource('folders', FolderController::class);
        
        // Invoices Management
        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{invoice}/download-pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.download-pdf');
        Route::post('invoices/{invoice}/regenerate-pdf', [InvoiceController::class, 'regeneratePdf'])->name('invoices.regenerate-pdf');
        
        // Company Management
        Route::resource('companies', CompanyController::class);
        
        // File Management
        Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');
        Route::get('files/{file}/preview', [FileController::class, 'preview'])->name('files.preview');
        Route::delete('files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
        Route::post('/folders/{folder}/upload', [FileController::class, 'store'])->name('files.upload');
        Route::post('/folders/{folder}/chunk', [FileController::class, 'storeChunk'])->name('files.chunk');
});

// Routes that don't require subscription (plans and profile) - MUST COME AFTER PROTECTED ROUTES
Route::middleware(['auth', 'verified', \App\Http\Middleware\UserMiddleware::class])->prefix('user')->name('user.')->group(function () {
    // Subscription Plans
    Route::get('/subscription/plans', [SubscriptionController::class, 'showPlans'])->name('subscription.plans');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Admin Folder Management
    Route::resource('folders', AdminFolderController::class);
    Route::get('/folders/create/in/{parent}', [AdminFolderController::class, 'createIn'])->name('folders.create.in');
    
    // Admin User Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/verify', [AdminUserController::class, 'verifyUser'])->name('users.verify');
    
    // Accountant Assignment Management
    Route::get('/users/{accountant}/assign-users', [AdminUserController::class, 'assignUsers'])->name('users.assign');
    Route::post('/users/{accountant}/assign-users', [AdminUserController::class, 'updateAssignedUsers'])->name('users.assign.update');
    Route::get('/users/{accountant}/assign-companies', [AdminUserController::class, 'assignCompanies'])->name('users.assign-companies');
    Route::post('/users/{accountant}/assign-companies', [AdminUserController::class, 'updateAssignedCompanies'])->name('users.assign-companies.update');
    
    // Admin Subscription Management
    Route::get('/users/{user}/subscription', [AdminUserController::class, 'manageSubscription'])->name('users.subscription.manage');
    Route::post('/users/{user}/subscription', [AdminUserController::class, 'updateSubscription'])->name('users.subscription.update');
    Route::get('/users/{user}/subscription/create', [AdminUserController::class, 'manageSubscription'])->name('users.subscription.create');
    
    // Subscription Debugging
    Route::get('/users/{user}/subscription/debug', [AdminUserController::class, 'debugSubscription'])->name('users.subscription.debug');
    
    // Admin File Management
    Route::get('files/{file}/download', [AdminFileController::class, 'download'])->name('files.download');
    Route::get('files/{file}/preview', [AdminFileController::class, 'preview'])->name('files.preview');
    Route::delete('files/{file}', [AdminFileController::class, 'destroy'])->name('files.destroy');
    Route::post('folders/{folder}/files', [AdminFileController::class, 'store'])->name('folders.files.store');

    // Bulk delete routes
    Route::delete('folders/bulk-destroy', [AdminFolderController::class, 'bulkDestroy'])->name('folders.bulk-destroy');
    Route::delete('files/bulk-destroy', [AdminFileController::class, 'bulkDestroy'])->name('files.bulk-destroy');

    // Action to create missing folder structures
    Route::post('folders/create-missing-structures', [AdminFolderController::class, 'createMissingStructures'])->name('folders.create-missing-structures');

    // Action to delete all folders and files (USE WITH EXTREME CAUTION)
    Route::post('folders/delete-all', [AdminFolderController::class, 'deleteAll'])->name('folders.delete-all');

    // Admin Company Management
    Route::resource('companies', AdminCompanyController::class);
    Route::get('companies/duplicates/find', [AdminCompanyController::class, 'findDuplicates'])->name('companies.duplicates');
    Route::post('companies/duplicates/merge', [AdminCompanyController::class, 'mergeDuplicates'])->name('companies.merge');
    
    // Company Accountant Assignment
    Route::get('companies/{company}/assign-accountants', [AdminCompanyController::class, 'assignAccountants'])->name('companies.assign.accountants');
    Route::post('companies/{company}/update-accountants', [AdminCompanyController::class, 'updateAccountants'])->name('companies.update.accountants');
    
    // Admin Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('settings');
    Route::delete('/settings', [\App\Http\Controllers\Admin\AdminProfileController::class, 'destroy'])->name('settings.destroy');
    Route::patch('/settings', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('settings.update');
});

// Accountant Routes
Route::prefix('/accountant')
    ->name('accountant.')
    ->middleware(['auth', \App\Http\Middleware\AccountantMiddleware::class])
    ->group(function () {
    // Accountant Dashboard
    Route::get('/dashboard', [AccountantDashboardController::class, 'index'])->name('dashboard');
    
    // Accountant User Management
    Route::get('/users', [AccountantUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AccountantUserController::class, 'show'])->name('users.show');
    Route::get('/users/{userId}/folders/{folderId}', [AccountantUserController::class, 'viewFolder'])->name('users.viewFolder');
    
    // Accountant File Management
    Route::get('/files/{file}/download', [\App\Http\Controllers\Accountant\AccountantFileController::class, 'download'])->name('files.download');
    Route::get('/files/{file}/preview', [\App\Http\Controllers\Accountant\AccountantFileController::class, 'preview'])->name('files.preview');
    
    // Accountant Company Management
    Route::get('/companies', [AccountantCompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/{company}', [AccountantCompanyController::class, 'show'])->name('companies.show');

    // Accountant Profile Management
    Route::get('/profile', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
