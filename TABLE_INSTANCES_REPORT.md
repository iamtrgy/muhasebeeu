# Table Instances Report

This report contains all blade files that use table elements or table components in the resources/views directory, excluding ui-showcase.blade.php and data-table-demo.blade.php.

## Summary

Total files found: 40 files containing table elements

## Table Usage Categories

### 1. Files Using Raw HTML Tables (`<table>`, `<thead>`, `<tbody>`, `<tr>`, `<td>`, `<th>`)

These files use traditional HTML table elements directly:

1. **User Module Tables**
   - `/resources/views/user/invoices/index.blade.php` - Invoice listing table
   - `/resources/views/user/invoices/create.blade.php` - Invoice items table
   - `/resources/views/user/invoices/show.blade.php` - Invoice details display
   - `/resources/views/user/invoices/edit.blade.php` - Invoice editing table
   - `/resources/views/user/companies/index.blade.php` - Companies listing
   - `/resources/views/user/company_temp/index.blade.php` - Temporary companies listing
   - `/resources/views/user/clients/index.blade.php` - Clients listing
   - `/resources/views/user/customers/index.blade.php` - Customers listing
   - `/resources/views/user/subscriptions/plans.blade.php` - Subscription plans table
   - `/resources/views/user/folders/show.blade.php` - Folder contents table

2. **Admin Module Tables**
   - `/resources/views/admin/users.blade.php` - Basic user management table
   - `/resources/views/admin/users/show.blade.php` - User details view
   - `/resources/views/admin/users/subscription_debug.blade.php` - Subscription debugging
   - `/resources/views/admin/users/partials/tab-documents.blade.php` - Documents tab
   - `/resources/views/admin/users/partials/tab-companies.blade.php` - Companies tab
   - `/resources/views/admin/companies/duplicates.blade.php` - Duplicate companies

3. **Accountant Module Tables**
   - `/resources/views/accountant/dashboard/index.blade.php` - Dashboard tables

4. **Tax Calendar Module**
   - `/resources/views/tax-calendar/index.blade.php` - Tax calendar table
   - `/resources/views/tax-calendar/tasks/index.blade.php` - Tasks listing
   - `/resources/views/tax-calendar/accountant/reviews/index.blade.php` - Reviews listing

### 2. Files Using Custom Table Components

#### Using `x-admin.table` Component System:
- `/resources/views/admin/companies/index.blade.php`
- `/resources/views/admin/users/index.blade.php`
- `/resources/views/admin/companies/duplicates.blade.php`
- `/resources/views/admin/users/partials/tab-companies.blade.php`

#### Using `x-ui.table` Component System:
- `/resources/views/admin/folders/show.blade.php`
- `/resources/views/admin/folders/index.blade.php`
- `/resources/views/accountant/companies/show.blade.php`
- `/resources/views/accountant/companies/index.blade.php`
- `/resources/views/accountant/users/show.blade.php`
- `/resources/views/accountant/users/folder.blade.php`
- `/resources/views/accountant/users/index.blade.php`
- `/resources/views/admin/users/show.blade.php`
- `/resources/views/admin/users/subscription-debug.blade.php`

#### Using `x-data-table` Component:
- `/resources/views/components/composite/file-browser.blade.php`
- `/resources/views/components/composite/data-table.blade.php`
- `/resources/views/components/tables/data-table.blade.php`

### 3. Table Component Files (Building Blocks)

These are the component files that define table structures:

1. **UI Table Components** (`/resources/views/components/ui/table/`)
   - `base.blade.php`
   - `header.blade.php`
   - `body.blade.php`
   - `row.blade.php`
   - `head-cell.blade.php`
   - `cell.blade.php`
   - `action-cell.blade.php`
   - `empty-state.blade.php`

2. **Admin Table Components** (`/resources/views/components/admin/table/`)
   - `table.blade.php`
   - `th.blade.php`
   - `tr.blade.php`
   - `td.blade.php`

3. **Folder Components** (Table-like structures)
   - `/resources/views/components/folder/file-list.blade.php`
   - `/resources/views/components/folder/file-row.blade.php`
   - `/resources/views/components/folder/folder-list.blade.php`
   - `/resources/views/components/folder/folder-row.blade.php`
   - `/resources/views/components/folder/table-header.blade.php`

4. **Other Table Components**
   - `/resources/views/components/cards/files-table-card.blade.php`
   - `/resources/views/components/tables/table-actions.blade.php`
   - `/resources/views/components/tables/table-row.blade.php`

## Recommendations for Migration

1. **Priority 1 - Raw HTML Tables**: These files should be migrated first as they use direct HTML table elements:
   - User invoice management views
   - User client/customer management views
   - Basic admin user management (`admin/users.blade.php`)

2. **Priority 2 - Mixed Usage**: Some files use partial components but still have raw HTML:
   - Tax calendar views
   - Accountant dashboard

3. **Already Using Components**: These files are already using modern component systems and may only need minor updates:
   - Most admin views using `x-admin.table`
   - Accountant views using `x-ui.table`

## Table Component Systems in Use

1. **x-admin.table**: Used in admin module for consistent styling
2. **x-ui.table**: More modern component system used in newer views
3. **x-data-table**: Advanced data table with sorting/filtering capabilities
4. **Raw HTML tables**: Legacy approach still used in many views

The migration should standardize on one component system (likely `x-ui.table` as it appears to be the newest) for consistency across the application.