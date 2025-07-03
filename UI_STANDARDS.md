# UI Component Standardization Guide

## Overview
This document defines the standardized UI components and patterns to be used throughout the application. All pages should follow these standards to ensure consistency, maintainability, and professional appearance.

**🚨 CRITICAL: Always use the new layout system as demonstrated in `/layout-demo`**

## Table of Contents
1. [Layout System](#layout-system)
2. [Card Components](#card-components)
3. [Table Components](#table-components)
4. [Button Components](#button-components)
5. [Avatar Components](#avatar-components)
6. [Badge Components](#badge-components)
7. [Form Components](#form-components)
8. [Modal Components](#modal-components)
9. [Color System](#color-system)
10. [Spacing and Layout](#spacing-and-layout)
11. [Interactive States](#interactive-states)
12. [Typography](#typography)
13. [Icons](#icons)
14. [Migration Checklist](#migration-checklist)
15. [Examples](#examples)

---

## Layout System

### 1. Layout Components
**ALWAYS use the new layout system as demonstrated in `/layout-demo`**

```blade
{{-- Role-specific layouts --}}
<x-accountant.layout title="Page Title" :breadcrumbs="[...]">
    <div class="space-y-6">
        {{-- Page content here --}}
    </div>
</x-accountant.layout>

<x-admin.layout title="Page Title" :breadcrumbs="[...]">
    <div class="space-y-6">
        {{-- Page content here --}}
    </div>
</x-admin.layout>

<x-user.layout title="Page Title" :breadcrumbs="[...]">
    <div class="space-y-6">
        {{-- Page content here --}}
    </div>
</x-user.layout>
```

### 2. Content Structure
- **Container**: Always use `<div class="space-y-6">` as main content container
- **Grid Layouts**: Use `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6` for responsive layouts
- **No max-width**: Don't use `max-w-7xl mx-auto` - let the layout system handle width

### 3. Breadcrumbs
```blade
:breadcrumbs="[
    ['title' => __('Home'), 'href' => route('dashboard'), 'first' => true],
    ['title' => __('Current Page')]
]"
```
- First breadcrumb must have `'first' => true` to prevent arrow display
- Use `route()` helper for links
- Use `__()` for translations

## Card Components

### 1. Basic Card Structure
```blade
<x-ui.card.base>
    <x-ui.card.header>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Card Title</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Optional description</p>
    </x-ui.card.header>
    <x-ui.card.body>
        {{-- Card content --}}
    </x-ui.card.body>
</x-ui.card.base>
```

### 2. Stats Cards (Dashboard)
```blade
<x-ui.card.base class="hover:shadow-lg transition-shadow">
    <x-ui.card.body class="p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                {{-- Icon SVG --}}
            </div>
            <div class="ml-5 flex-1">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Stat Label
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $statValue }}
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <x-ui.button.primary size="sm" href="{{ route('...') }}" class="w-full">
                Action Button
            </x-ui.button.primary>
        </div>
    </x-ui.card.body>
</x-ui.card.base>
```

## Table Components

### 1. Standard Table Structure
```blade
<x-ui.card.base>
    <x-ui.card.header>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Table Title</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Table description</p>
    </x-ui.card.header>
    <x-ui.card.body>
        <x-ui.table.base>
            <x-slot name="head">
                <x-ui.table.head-cell>Column 1</x-ui.table.head-cell>
                <x-ui.table.head-cell>Column 2</x-ui.table.head-cell>
                <x-ui.table.head-cell class="text-right">Actions</x-ui.table.head-cell>
            </x-slot>
            <x-slot name="body">
                @foreach($items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <x-ui.table.cell>{{ $item->name }}</x-ui.table.cell>
                        <x-ui.table.cell>{{ $item->value }}</x-ui.table.cell>
                        <x-ui.table.action-cell>
                            <x-ui.button.secondary size="sm" href="{{ route('...', $item) }}">
                                View
                            </x-ui.button.secondary>
                        </x-ui.table.action-cell>
                    </tr>
                @endforeach
            </x-slot>
        </x-ui.table.base>
    </x-ui.card.body>
</x-ui.card.base>
```

### 2. Empty State
```blade
<x-ui.table.empty-state>
    <x-slot name="icon">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {{-- Icon path --}}
        </svg>
    </x-slot>
    <x-slot name="title">No Items Found</x-slot>
    <x-slot name="description">Description of empty state</x-slot>
</x-ui.table.empty-state>
```

## Button Components

### 1. Button Variants
```blade
{{-- Primary actions --}}
<x-ui.button.primary href="{{ route('...') }}">Primary Action</x-ui.button.primary>

{{-- Secondary actions --}}
<x-ui.button.secondary href="{{ route('...') }}">Secondary Action</x-ui.button.secondary>

{{-- Destructive actions --}}
<x-ui.button.danger>Delete</x-ui.button.danger>
```

### 2. Button Sizes
```blade
<x-ui.button.primary size="sm">Small</x-ui.button.primary>
<x-ui.button.primary size="md">Medium (default)</x-ui.button.primary>
<x-ui.button.primary size="lg">Large</x-ui.button.primary>
```

### 3. Button with Icons
```blade
<x-ui.button.primary>
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {{-- Icon path --}}
    </svg>
    Button Text
</x-ui.button.primary>
```

## Avatar Components

### 1. Avatar Usage
```blade
{{-- Auto-generated from name --}}
<x-ui.avatar name="{{ $user->name }}" size="sm" />
<x-ui.avatar name="{{ $user->name }}" size="md" />
<x-ui.avatar name="{{ $user->name }}" size="lg" />

{{-- With image --}}
<x-ui.avatar :src="$user->avatar_url" name="{{ $user->name }}" size="md" />
```

## Badge Components

### 1. Status Badges
```blade
<x-ui.badge variant="success">Active</x-ui.badge>
<x-ui.badge variant="warning">Pending</x-ui.badge>
<x-ui.badge variant="danger">Rejected</x-ui.badge>
<x-ui.badge variant="secondary">Draft</x-ui.badge>
```

## Form Components

### 1. Form Input Structure
```blade
<x-ui.form.group>
    <x-ui.form.input 
        name="email" 
        type="email" 
        label="Email Address"
        placeholder="Enter your email"
        :value="old('email')"
        :error="$errors->first('email')"
        required
    />
</x-ui.form.group>
```

### 2. Select Components
```blade
<x-ui.form.select name="status" label="Status" :error="$errors->first('status')">
    <option value="">Select Status</option>
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
</x-ui.form.select>
```

## Modal Components

### 1. Event-based Modals (Recommended)
```blade
{{-- Modal definition --}}
<x-ui.modal.base name="edit-user">
    <h3 class="text-lg font-medium mb-4">Edit User</h3>
    <div class="mt-4">
        {{-- Modal content --}}
    </div>
    <div class="mt-6 flex justify-end gap-3">
        <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'edit-user')">
            Cancel
        </x-ui.button.secondary>
        <x-ui.button.primary>
            Save
        </x-ui.button.primary>
    </div>
</x-ui.modal.base>

{{-- Trigger button --}}
<x-ui.button.primary x-on:click="$dispatch('open-modal', 'edit-user')">
    Edit User
</x-ui.button.primary>
```

## Color System

### 1. Primary Colors
- **Primary**: `indigo-500/600/700` (buttons, links, focus states)
- **Secondary**: `gray-500/600/700` (secondary buttons, borders)
- **Success**: `emerald-500` (success states, positive actions)
- **Warning**: `amber-500` (warning states, caution)
- **Danger**: `red-500/600/700` (destructive actions, errors)

### 2. Text Colors
- **Primary Text**: `text-gray-900 dark:text-gray-100`
- **Secondary Text**: `text-gray-500 dark:text-gray-400`
- **Muted Text**: `text-gray-400 dark:text-gray-500`

### 3. Background Colors
- **Card Background**: `bg-white dark:bg-gray-800`
- **Page Background**: `bg-gray-50 dark:bg-gray-900`
- **Hover States**: `hover:bg-gray-50 dark:hover:bg-gray-700`

## Spacing and Layout

### 1. Standard Spacing
- **Section Spacing**: `space-y-6` (24px)
- **Card Padding**: `p-6` (24px)
- **Button Spacing**: `gap-3` (12px)
- **Grid Gaps**: `gap-6` (24px)

### 2. Responsive Grid
```blade
{{-- 1 column on mobile, 2 on tablet, 3 on desktop --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {{-- Grid items --}}
</div>

{{-- Stats cards (responsive) --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Stat cards --}}
</div>
```

## Interactive States

### 1. Hover Effects
```blade
{{-- Cards --}}
class="hover:shadow-lg transition-shadow"

{{-- Table rows --}}
class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"

{{-- List items --}}
class="p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
```

### 2. Focus States
All interactive elements automatically inherit focus styles from the base components.

## Typography

### 1. Headings
```blade
{{-- Page titles (in layout) --}}
title="Page Title"

{{-- Section headings --}}
<h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">

{{-- Card titles --}}
<h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
```

### 2. Body Text
```blade
{{-- Regular text --}}
<p class="text-sm text-gray-900 dark:text-gray-100">

{{-- Secondary text --}}
<p class="text-sm text-gray-500 dark:text-gray-400">

{{-- Descriptions --}}
<p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
```

## Icons

### 1. Icon Standards
- **Size**: `h-4 w-4` for buttons, `h-5 w-5` for table icons, `h-6 w-6` for headers
- **Color**: Inherit from parent or `text-gray-400` for decorative icons
- **SVG Icons**: Use Heroicons v2 for consistency

### 2. Icon in Buttons
```blade
<x-ui.button.primary>
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="..."/>
    </svg>
    Button Text
</x-ui.button.primary>
```

## Migration Checklist

When updating a page to use the new UI standards:

### 1. Layout Migration
- [ ] Replace old layout with role-specific layout (`<x-accountant.layout>`)
- [ ] Update breadcrumb structure with `'first' => true`
- [ ] Use `space-y-6` container
- [ ] Remove `max-w-7xl mx-auto` wrappers

### 2. Component Migration
- [ ] Replace manual cards with `<x-ui.card.base>`
- [ ] Update tables to use `<x-ui.table.base>`
- [ ] Convert buttons to `<x-ui.button.*>`
- [ ] Replace manual badges with `<x-ui.badge>`
- [ ] Update avatars to use `<x-ui.avatar>`

### 3. Testing
- [ ] Test responsive behavior
- [ ] Verify dark mode compatibility
- [ ] Check hover/focus states
- [ ] Validate accessibility

### 4. Build Process
```bash
# ALWAYS run after UI changes
npm run build

# Clear view cache if needed
php artisan view:clear
```

## Examples

See these files for reference implementations:
- **Dashboard**: `/resources/views/accountant/dashboard/index.blade.php`
- **Companies List**: `/resources/views/accountant/companies/index.blade.php`
- **Layout Demo**: `/resources/views/layout-demo.blade.php`
- **Component Showcase**: `/resources/views/ui-showcase.blade.php`

## Dos and Don'ts

### ✅ DO
- Use the new layout system consistently
- Follow the component patterns exactly
- Use proper spacing and responsive classes
- Include hover and transition effects
- Use semantic HTML elements
- Follow accessibility best practices

### ❌ DON'T
- Mix old and new component patterns
- Use manual HTML when components exist
- Ignore responsive design patterns
- Skip dark mode compatibility
- Use arbitrary spacing values
- Create custom components without approval

## Component Usage Examples

### Button
```blade
{{-- Primary Button --}}
<x-ui.button.primary>
    Save Changes
</x-ui.button.primary>

{{-- With custom size --}}
<x-ui.button.secondary size="lg">
    Cancel
</x-ui.button.secondary>

{{-- Danger button with icon --}}
<x-ui.button.danger>
    <svg class="w-4 h-4 mr-2">...</svg>
    Delete
</x-ui.button.danger>
```

### Modal
```blade
{{-- Event-based modal (recommended) --}}
<x-ui.modal.base name="edit-user">
    <h3 class="text-lg font-medium mb-4">Edit User</h3>
    <div class="mt-4">
        {{-- Form content --}}
    </div>
    <div class="mt-6 flex justify-end gap-3">
        <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'edit-user')">
            Cancel
        </x-ui.button.secondary>
        <x-ui.button.primary>
            Save
        </x-ui.button.primary>
    </div>
</x-ui.modal.base>

{{-- Open the modal --}}
<x-ui.button.primary x-on:click="$dispatch('open-modal', 'edit-user')">
    Edit User
</x-ui.button.primary>
```

## Development Routes
- `/ui-showcase` - Component showcase page (admin only)
- `/layout-demo` - Layout components demo with sidebar (admin only)

## Commands to Run
⚠️ **ALWAYS RUN AFTER UI CHANGES** ⚠️
```bash
# 1. REQUIRED: Build Tailwind CSS after any UI component changes
npm run build

# 2. Clear view cache if needed
php artisan view:clear

# 3. Run tests to ensure nothing is broken
php artisan test
```

## Support

For questions or clarifications about UI standards:
1. Check the component showcase at `/ui-showcase`
2. Review the layout demo at `/layout-demo`
3. Reference this documentation
4. Follow existing implementations in updated pages

---

**This UI Standardization Guide should be treated as a living document and updated as the design system evolves.**