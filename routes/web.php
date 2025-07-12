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
use App\Http\Controllers\User\InvoiceController as UserInvoiceController;
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
use App\Http\Controllers\TaxCalendarTaskController;
use App\Http\Controllers\TaskMessageController;
use App\Http\Controllers\TaxCalendarReviewController;
use App\Http\Controllers\PaymentController;

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
Route::middleware(['auth', \App\Http\Middleware\RedirectAdminToDashboard::class])->group(function () {
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

// Routes that don't require subscription (plans and profile) - MUST COME AFTER PROTECTED ROUTES
Route::middleware(['auth', 'verified', \App\Http\Middleware\UserMiddleware::class])->prefix('user')->name('user.')->group(function () {
    // Debug route (temporary)
    Route::get('/debug-subscription', function () {
        return view('debug-subscription');
    })->name('debug-subscription');
    // Subscription Plans
    Route::get('/subscription/plans', [SubscriptionController::class, 'showPlans'])->name('subscription.plans');
    Route::get('/subscription/{plan}/payment', [SubscriptionController::class, 'showPaymentForm'])->name('subscription.payment.form');
    Route::post('/subscription/create', [SubscriptionController::class, 'subscribe'])->name('subscription.create');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
    Route::get('/billing-portal', [SubscriptionController::class, 'billingPortal'])->name('subscription.billing.portal');
    
    // Profile routes (redirected to settings)
    Route::get('/profile', function() {
        return redirect()->route('user.settings', ['tab' => 'profile']);
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Settings routes
    Route::get('/settings', [\App\Http\Controllers\User\UserSettingsController::class, 'index'])->name('settings');
    Route::patch('/settings/notifications', [\App\Http\Controllers\User\UserSettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::patch('/settings/appearance', [\App\Http\Controllers\User\UserSettingsController::class, 'updateAppearance'])->name('settings.appearance');
    
    // AI History - Available without subscription
    Route::get('/ai-analysis/history', [\App\Http\Controllers\User\AIDocumentController::class, 'history'])->name('ai-analysis.history');
});

// All routes that require subscription
Route::middleware(['auth', 'verified', 'subscribed', \App\Http\Middleware\EnsureOnboardingIsComplete::class])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/current-month-folder', [FolderController::class, 'currentMonthFolder'])->name('current-month-folder');
        
        // Simplified Tax Calendar Routes
        Route::get('/tax-calendar', [TaxCalendarTaskController::class, 'index'])->name('tax-calendar.index');
        Route::get('/tax-calendar/{task}', [TaxCalendarTaskController::class, 'show'])->name('tax-calendar.show');
        Route::patch('/tax-calendar/{task}/checklist', [TaxCalendarTaskController::class, 'updateChecklist'])->name('tax-calendar.update-checklist');
        Route::patch('/tax-calendar/{task}/notes', [TaxCalendarTaskController::class, 'updateNotes'])->name('tax-calendar.update-notes');
        Route::patch('/tax-calendar/{task}/toggle-complete', [TaxCalendarTaskController::class, 'toggleComplete'])->name('tax-calendar.toggle-complete');
        
        // Clients Management
        Route::resource('clients', \App\Http\Controllers\User\UserClientController::class);
        
        // Customers Management
        Route::resource('userclients', \App\Http\Controllers\User\CustomerController::class);
        
        // Folders Management
        Route::resource('folders', FolderController::class);
        
        // Invoices Management
        Route::resource('invoices', UserInvoiceController::class);
        Route::get('invoices/{invoice}/download-pdf', [UserInvoiceController::class, 'downloadPdf'])->name('invoices.download-pdf');
        Route::post('invoices/{invoice}/regenerate-pdf', [UserInvoiceController::class, 'regeneratePdf'])->name('invoices.regenerate-pdf');
        Route::post('invoices/{invoice}/send', [UserInvoiceController::class, 'send'])->name('invoices.send');
        
        // Company Management
        Route::resource('companies', CompanyController::class);
        
        // File Management
        Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');
        Route::get('files/{file}/preview', [FileController::class, 'preview'])->name('files.preview');
        Route::patch('files/{file}/notes', [FileController::class, 'updateNotes'])->name('files.update-notes');
        Route::delete('files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
        Route::post('/folders/{folder}/upload', [FileController::class, 'store'])->name('files.upload');
        Route::post('/folders/{folder}/chunk', [FileController::class, 'storeChunk'])->name('files.chunk');
        
        // AI Document Analysis
        Route::post('files/{file}/analyze', [\App\Http\Controllers\User\AIDocumentController::class, 'analyze'])->name('files.analyze');
        Route::post('files/{file}/accept-suggestion', [\App\Http\Controllers\User\AIDocumentController::class, 'acceptSuggestion'])->name('files.accept-suggestion');
        Route::post('files/batch-analyze', [\App\Http\Controllers\User\AIDocumentController::class, 'batchAnalyze'])->name('files.batch-analyze');
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
    
    
    // Admin File Management
    Route::get('files/{file}/download', [AdminFileController::class, 'download'])->name('files.download');
    Route::get('files/{file}/preview', [AdminFileController::class, 'preview'])->name('files.preview');
    Route::patch('files/{file}/notes', [AdminFileController::class, 'updateNotes'])->name('files.update-notes');
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
    
    // Admin Tax Calendar Routes
    Route::get('/tax-calendar', [TaxCalendarTaskController::class, 'index'])->name('tax-calendar.index');
    Route::get('/tax-calendar/{task}', [TaxCalendarTaskController::class, 'show'])->name('tax-calendar.show');
    Route::patch('/tax-calendar/{task}/checklist', [TaxCalendarTaskController::class, 'updateChecklist'])->name('tax-calendar.update-checklist');
    Route::patch('/tax-calendar/{task}/notes', [TaxCalendarTaskController::class, 'updateNotes'])->name('tax-calendar.update-notes');
    Route::patch('/tax-calendar/{task}/toggle-complete', [TaxCalendarTaskController::class, 'toggleComplete'])->name('tax-calendar.toggle-complete');
});

// Accountant Routes
Route::prefix('/accountant')
    ->name('accountant.')
    ->middleware(['auth', \App\Http\Middleware\AccountantMiddleware::class])
    ->group(function () {
        // Accountant Dashboard
        Route::get('/dashboard', [AccountantDashboardController::class, 'index'])->name('dashboard');
        
        // Simplified Tax Calendar for Accountants
        Route::get('/tax-calendar', [TaxCalendarTaskController::class, 'index'])->name('tax-calendar.index');
        
        // Tax Calendar Reviews - MUST BE BEFORE GENERIC {task} ROUTE
        Route::get('/tax-calendar/reviews', [TaxCalendarReviewController::class, 'index'])->name('tax-calendar.reviews');
        Route::get('/tax-calendar/reviews/{task}', [TaxCalendarReviewController::class, 'show'])->name('tax-calendar.reviews.show');
        Route::put('/tax-calendar/reviews/{task}', [TaxCalendarReviewController::class, 'update'])->name('tax-calendar.reviews.update');
        Route::post('/tax-calendar/reviews/{task}/comments', [TaxCalendarReviewController::class, 'store'])->name('tax-calendar.reviews.comments');
        
        // Generic tax calendar task route - MUST BE AFTER SPECIFIC ROUTES
        Route::get('/tax-calendar/{task}', [TaxCalendarTaskController::class, 'show'])->name('tax-calendar.show');
        
        
        // Accountant File Management
        Route::get('/files/{file}/download', [\App\Http\Controllers\Accountant\AccountantFileController::class, 'download'])->name('files.download');
        Route::get('/files/{file}/preview', [\App\Http\Controllers\Accountant\AccountantFileController::class, 'preview'])->name('files.preview');
        Route::patch('/files/{file}/notes', [\App\Http\Controllers\Accountant\AccountantFileController::class, 'updateNotes'])->name('files.update-notes');
        
        // Accountant Company Management
        Route::get('/companies', [AccountantCompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/{company}', [AccountantCompanyController::class, 'show'])->name('companies.show');
        Route::get('/companies/{company}/folders/{folder}', [AccountantCompanyController::class, 'viewFolder'])->name('companies.folders.show');

        // Accountant Profile Management
        Route::get('/profile', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::delete('/profile', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'destroy'])->name('profile.destroy');
        
        // Accountant Settings
        Route::get('/settings', [\App\Http\Controllers\Accountant\AccountantSettingsController::class, 'index'])->name('settings');
        Route::patch('/settings/notifications', [\App\Http\Controllers\Accountant\AccountantSettingsController::class, 'updateNotifications'])->name('settings.notifications');
        Route::patch('/settings/appearance', [\App\Http\Controllers\Accountant\AccountantSettingsController::class, 'updateAppearance'])->name('settings.appearance');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/subscription/complete', [SubscriptionController::class, 'complete'])
        ->name('user.subscription.complete');
});

// Test route for modal debugging (remove in production)
Route::middleware(['auth', 'admin'])->get('/test-modals', function () {
    return view('test-modals');
})->name('test.modals');

// UI Component Showcase (for development)
Route::middleware(['auth', 'admin'])->get('/ui-showcase', function () {
    return view('ui-showcase');
})->name('ui.showcase');

// Layout Demo (for development)
Route::middleware(['auth', 'admin'])->get('/layout-demo', function () {
    return view('layout-demo');
})->name('layout.demo');

// Public payment page for guests (no authentication required)
Route::get('/pay/{invoice}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');

// Stripe webhook endpoint (must be outside auth middleware)
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])->name('cashier.webhook');

require __DIR__.'/auth.php';
