# UI Component Usage Audit Report

## Overview
Total Blade files scanned: 212 files in `resources/views` directory

## 1. Old Button Components

### x-primary-button
- **Total occurrences**: 17 files
- **Files using it**:
  - `resources/views/admin/folders/create.blade.php`
  - `resources/views/admin/profile/partials/update-password-form.blade.php`
  - `resources/views/admin/profile/partials/update-profile-information-form.blade.php`
  - `resources/views/auth/confirm-password.blade.php`
  - `resources/views/auth/forgot-password.blade.php`
  - `resources/views/auth/login.blade.php`
  - `resources/views/auth/register.blade.php`
  - `resources/views/auth/reset-password.blade.php`
  - `resources/views/auth/verify-email.blade.php`
  - `resources/views/ui-showcase.blade.php`
  - `resources/views/user/clients/create.blade.php`
  - `resources/views/user/clients/edit.blade.php`
  - `resources/views/user/customers/create.blade.php`
  - `resources/views/user/invoices/create.blade.php`
  - `resources/views/user/invoices/edit.blade.php`
  - `resources/views/user/profile/partials/update-password-form.blade.php`
  - `resources/views/user/profile/partials/update-profile-information-form.blade.php`

**Sample usage**:
```blade
<x-primary-button>
    {{ __('Create Client') }}
</x-primary-button>
```

### x-secondary-button
- **Total occurrences**: 5 files
- **Files using it**:
  - `resources/views/ui-showcase.blade.php`
  - `resources/views/user/clients/create.blade.php`
  - `resources/views/user/clients/edit.blade.php`
  - `resources/views/user/customers/create.blade.php`
  - `resources/views/user/profile/partials/delete-user-form.blade.php`

**Sample usage**:
```blade
<x-secondary-button class="mr-2" onclick="window.location='{{ route('user.clients.index') }}'">
    {{ __('Cancel') }}
</x-secondary-button>
```

### x-danger-button
- **Total occurrences**: 3 files
- **Files using it**:
  - `resources/views/admin/profile/partials/delete-user-form.blade.php`
  - `resources/views/ui-showcase.blade.php`
  - `resources/views/user/profile/partials/delete-user-form.blade.php`

**Sample usage**:
```blade
<x-danger-button>Old Danger</x-danger-button>
```

## 2. Old Modal Components

### x-modal
- **Total occurrences**: 1 file
- **Files using it**:
  - `resources/views/user/profile/partials/delete-user-form.blade.php`

**Sample usage**:
```blade
<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('user.profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')
        
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Are you sure you want to delete your account?') }}
        </h2>
    </form>
</x-modal>
```

## 3. Old Form Components

### x-text-input
- **Total occurrences**: 19 files
- **Files using it**:
  - `resources/views/admin/companies/create.blade.php`
  - `resources/views/admin/companies/edit.blade.php`
  - `resources/views/admin/folders/create.blade.php`
  - `resources/views/admin/folders/edit.blade.php`
  - `resources/views/admin/profile/partials/update-password-form.blade.php`
  - `resources/views/admin/profile/partials/update-profile-information-form.blade.php`
  - `resources/views/admin/users/edit.blade.php`
  - `resources/views/auth/confirm-password.blade.php`
  - `resources/views/auth/forgot-password.blade.php`
  - `resources/views/auth/reset-password.blade.php`
  - `resources/views/user/clients/create.blade.php`
  - `resources/views/user/clients/edit.blade.php`
  - `resources/views/user/customers/create.blade.php`
  - `resources/views/user/customers/edit.blade.php`
  - `resources/views/user/invoices/create.blade.php`
  - `resources/views/user/invoices/edit.blade.php`
  - `resources/views/user/profile/partials/delete-user-form.blade.php`
  - `resources/views/user/profile/partials/update-password-form.blade.php`
  - `resources/views/user/profile/partials/update-profile-information-form.blade.php`

### x-input-label
- **Total occurrences**: 19 files (same as x-text-input)

### x-input-error
- **Total occurrences**: 19 files (same as x-text-input)

**Sample usage**:
```blade
<div>
    <x-input-label for="name" :value="__('Client Name')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>
```

## 4. Native Table Elements

### <table> tags
- **Total occurrences**: 19 files
- **Files using it**:
  - `resources/views/accountant/dashboard/index.blade.php`
  - `resources/views/admin/companies/duplicates.blade.php`
  - `resources/views/admin/users/partials/tab-documents.blade.php`
  - `resources/views/components/admin/table.blade.php`
  - `resources/views/components/cards/files-table-card.blade.php`
  - `resources/views/components/folder/file-list.blade.php`
  - `resources/views/components/folder/folder-list.blade.php`
  - `resources/views/components/ui/table/base.blade.php`
  - `resources/views/tax-calendar/accountant/reviews/index.blade.php`
  - `resources/views/tax-calendar/index.blade.php`
  - `resources/views/tax-calendar/tasks/index.blade.php`
  - `resources/views/user/clients/index.blade.php`
  - `resources/views/user/customers/index.blade.php`
  - `resources/views/user/folders/show.blade.php`
  - `resources/views/user/invoices/create.blade.php`
  - `resources/views/user/invoices/edit.blade.php`
  - `resources/views/user/invoices/index.blade.php`
  - `resources/views/user/invoices/show.blade.php`
  - `resources/views/user/subscriptions/plans.blade.php`

**Sample usage**:
```blade
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                {{ __('Name') }}
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                {{ __('Email') }}
            </th>
        </tr>
    </thead>
</table>
```

## 5. Old Alert Patterns

### Custom Alert Divs
- **Total occurrences**: 4 files
- **Files using it**:
  - `resources/views/admin/companies/duplicates.blade.php`
  - `resources/views/admin/companies/index.blade.php`
  - `resources/views/user/companies/show.blade.php`
  - `resources/views/user/subscriptions/payment.blade.php`

**Sample usage**:
```blade
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif
```

## 6. New UI Components Already in Use

### Components already migrated:
- **x-ui.button**: 11 files
- **x-ui.modal**: 6 files
- **x-ui.table**: 5 files
- **x-ui.form**: 9 files
- **x-ui.alert**: 2 files

## Migration Priority

Based on the audit, here's the recommended migration priority:

1. **High Priority (Most Used)**:
   - Form components (x-text-input, x-input-label, x-input-error) - 19 files each
   - Native tables - 19 files
   - Primary buttons - 17 files

2. **Medium Priority**:
   - Secondary buttons - 5 files
   - Alert patterns - 4 files
   - Danger buttons - 3 files

3. **Low Priority**:
   - Modal component - 1 file

## Key Observations

1. **Form Components**: The most widely used old components, appearing in admin, auth, and user sections
2. **Tables**: Used extensively across different modules (admin, user, tax-calendar, accountant)
3. **Buttons**: Primary buttons are heavily used in forms and authentication pages
4. **Alerts**: Using custom div-based alerts instead of a standardized component
5. **Modal**: Only one instance found, making it easy to migrate

## Recommended Migration Strategy

1. Start with creating standardized wrappers for form components since they're the most used
2. Create a table component migration guide for the 19 files using native tables
3. Batch migrate all button components by type (primary, secondary, danger)
4. Standardize alert patterns into a single x-ui.alert component
5. Update the single modal instance last

This audit provides a clear roadmap for systematically migrating all old components to the new UI system.