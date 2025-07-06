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
8. [Tab Components](#tab-components)
9. [Modal Components](#modal-components)
10. [Color System](#color-system)
11. [Spacing and Layout](#spacing-and-layout)
12. [Interactive States](#interactive-states)
13. [Typography](#typography)
14. [Icons](#icons)
15. [Migration Checklist](#migration-checklist)
16. [Examples](#examples)

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
                            {{-- Icon-only actions (recommended for tables) --}}
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('...', $item) }}" 
                                   class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                   title="View details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('...', $item) }}" 
                                   class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button type="button" 
                                        class="p-1 rounded-lg text-red-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            
                            {{-- OR Button with text (when space allows) --}}
                            {{-- <x-ui.button.secondary size="sm" href="{{ route('...', $item) }}">
                                View
                            </x-ui.button.secondary> --}}
                        </x-ui.table.action-cell>
                    </tr>
                @endforeach
            </x-slot>
        </x-ui.table.base>
    </x-ui.card.body>
</x-ui.card.base>
```

### 3. Table Action Patterns

#### Icon-only Actions (Recommended for tables)
```blade
{{-- Standard action icons --}}
<div class="flex items-center justify-end gap-2">
    {{-- View/Details --}}
    <a href="{{ route('...') }}" 
       class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
       title="View details">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </a>
    
    {{-- Edit --}}
    <a href="{{ route('...') }}" 
       class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
       title="Edit">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
    </a>
    
    {{-- Download --}}
    <a href="{{ route('...') }}" 
       class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
       title="Download">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </a>
    
    {{-- Delete (with confirmation) --}}
    <button type="button" 
            class="p-1 rounded-lg text-red-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
            title="Delete"
            onclick="confirm('Are you sure?') && document.getElementById('delete-form-{{ $item->id }}').submit()">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>
</div>
```

#### Single Action (View-only tables)
```blade
{{-- When there's only one primary action --}}
<a href="{{ route('...') }}" 
   class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
    View details
    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
</a>
```

#### Button Actions (When explicit actions are needed)
```blade
{{-- Use buttons for important actions or when space allows --}}
<div class="flex items-center gap-2">
    <x-ui.button.secondary size="sm" href="{{ route('...') }}">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        View
    </x-ui.button.secondary>
    
    <x-ui.button.danger size="sm">
        Delete
    </x-ui.button.danger>
</div>
```

#### Dropdown Actions (For multiple secondary actions)
```blade
{{-- Use dropdown for additional actions to reduce clutter --}}
<x-ui.dropdown.base align="right">
    <x-slot name="trigger">
        <button class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
        </button>
    </x-slot>
    
    {{-- Secondary actions --}}
    <x-ui.dropdown.item href="{{ route('...') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
        Manage Subscription
    </x-ui.dropdown.item>
    
    <x-ui.dropdown.divider />
    
    {{-- Destructive action with form --}}
    <form action="{{ route('...') }}" method="POST" onsubmit="return confirm('Are you sure?');">
        @csrf
        @method('DELETE')
        <x-ui.dropdown.item tag="button" type="submit">
            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span class="text-red-600">Delete</span>
        </x-ui.dropdown.item>
    </form>
</x-ui.dropdown.base>
```

### 2. Editable Table Cells

For tables that need inline editing functionality (like file notes, user details, etc.):

```blade
{{-- Basic editable text cell --}}
<x-ui.table.editable-cell 
    :value="$record->field_name ?? ''"
    placeholder="Click to add..."
    :route="route('model.update-field', $record)"
    field="field_name"
    type="text"
    :maxLength="255"
/>

{{-- Editable textarea cell for longer content --}}
<x-ui.table.editable-cell 
    :value="$file->notes ?? ''"
    placeholder="Add notes for accountant..."
    :route="route('user.files.update-notes', $file)"
    field="notes"
    type="textarea"
    :maxLength="1000"
/>
```

#### Editable Cell Features:
- **Click to edit**: Click anywhere on the cell to start editing
- **Auto-save**: Saves on blur or Enter/Ctrl+Enter
- **Cancel**: Press Escape to cancel changes
- **Visual feedback**: Loading spinner during save, success/error messages
- **Validation**: Client-side max length, server-side validation
- **Accessibility**: Proper focus management and keyboard navigation

#### Backend Requirements:
Your controller method must:
1. Accept PATCH requests with JSON content
2. Return JSON response with `success` boolean and `message`
3. Include proper authorization checks

```php
public function updateField(Request $request, Model $record)
{
    $this->authorize('update', $record);
    
    $validated = $request->validate([
        'field_name' => 'nullable|string|max:1000'
    ]);
    
    $record->update($validated);
    
    return response()->json([
        'success' => true,
        'message' => 'Updated successfully',
        'field_name' => $record->field_name
    ]);
}
```

### 3. Empty State
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

### 1. Form Structure Patterns

#### Vertical Form Layout (Most Common)
```blade
{{-- Use this pattern for vertical forms with proper spacing --}}
<form method="POST" action="{{ route('...') }}" class="space-y-6">
    @csrf
    
    {{-- Each input wrapped in a div for spacing --}}
    <div>
        <x-ui.form.input 
            name="name" 
            type="text" 
            label="Full Name"
            placeholder="Enter your name"
            :value="old('name')"
            required
        />
    </div>
    
    <div>
        <x-ui.form.input 
            name="email" 
            type="email" 
            label="Email Address"
            placeholder="Enter your email"
            :value="old('email')"
            required
        />
    </div>
    
    <div>
        <x-ui.form.select 
            name="role" 
            label="User Role"
            :options="$roles"
            placeholder="Select a role..."
        />
    </div>
</form>
```

#### Grid Form Layout
```blade
{{-- Use form.group for grid layouts --}}
<x-ui.form.group>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-ui.form.input 
            name="first_name" 
            label="First Name"
            required
        />
        
        <x-ui.form.input 
            name="last_name" 
            label="Last Name"
            required
        />
    </div>
</x-ui.form.group>
```

### 2. Individual Input Components
```blade
{{-- Basic input with all options --}}
<x-ui.form.input 
    name="email" 
    type="email" 
    label="Email Address"
    placeholder="Enter your email"
    :value="old('email')"
    :error="$errors->first('email')"
    helperText="We'll never share your email"
    required
    autocomplete="email"
>
    <x-slot name="leadingIcon">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
    </x-slot>
</x-ui.form.input>
```

### 3. Select Components
```blade
<x-ui.form.select 
    name="status" 
    label="Status" 
    :error="$errors->first('status')"
    placeholder="Choose status..."
>
    <option value="">Select Status</option>
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
</x-ui.form.select>
```

## Tab Components

### 1. Basic Tab Structure
```blade
<x-ui.tabs.base defaultTab="tab1">
    <x-ui.tabs.list>
        <x-ui.tabs.tab name="tab1" label="First Tab" />
        <x-ui.tabs.tab name="tab2" label="Second Tab" />
        <x-ui.tabs.tab name="tab3" label="Third Tab" />
    </x-ui.tabs.list>
    
    <x-ui.tabs.panels>
        <x-ui.tabs.panel name="tab1">
            <div class="space-y-4">
                {{-- First tab content --}}
            </div>
        </x-ui.tabs.panel>
        
        <x-ui.tabs.panel name="tab2">
            <div class="space-y-4">
                {{-- Second tab content --}}
            </div>
        </x-ui.tabs.panel>
        
        <x-ui.tabs.panel name="tab3">
            <div class="space-y-4">
                {{-- Third tab content --}}
            </div>
        </x-ui.tabs.panel>
    </x-ui.tabs.panels>
</x-ui.tabs.base>
```

### 2. Tabs with Icons
```blade
<x-ui.tabs.base defaultTab="notifications">
    <x-ui.tabs.list>
        <x-ui.tabs.tab name="notifications">
            <x-slot name="icon">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </x-slot>
            {{ __('Notifications') }}
        </x-ui.tabs.tab>
        
        <x-ui.tabs.tab name="security">
            <x-slot name="icon">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </x-slot>
            {{ __('Security') }}
        </x-ui.tabs.tab>
    </x-ui.tabs.list>
    
    <x-ui.tabs.panels>
        <x-ui.tabs.panel name="notifications">
            {{-- Notifications content --}}
        </x-ui.tabs.panel>
        
        <x-ui.tabs.panel name="security">
            {{-- Security content --}}
        </x-ui.tabs.panel>
    </x-ui.tabs.panels>
</x-ui.tabs.base>
```

### 3. Tabs with Badges
```blade
<x-ui.tabs.base defaultTab="messages">
    <x-ui.tabs.list>
        <x-ui.tabs.tab name="messages">
            <x-slot name="icon">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </x-slot>
            <x-slot name="badge">
                <x-ui.badge size="sm" variant="danger">3</x-ui.badge>
            </x-slot>
            {{ __('Messages') }}
        </x-ui.tabs.tab>
        
        <x-ui.tabs.tab name="alerts">
            <x-slot name="icon">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </x-slot>
            <x-slot name="badge">
                <x-ui.badge size="sm" variant="warning">12</x-ui.badge>
            </x-slot>
            {{ __('Alerts') }}
        </x-ui.tabs.tab>
    </x-ui.tabs.list>
    
    <x-ui.tabs.panels>
        {{-- Tab panels --}}
    </x-ui.tabs.panels>
</x-ui.tabs.base>
```

### 4. Tab Variants
```blade
{{-- Default underline tabs --}}
<x-ui.tabs.base defaultTab="tab1">
    {{-- Standard underline style --}}
</x-ui.tabs.base>

{{-- Pills tabs --}}
<x-ui.tabs.base defaultTab="tab1" variant="pills">
    <x-ui.tabs.list variant="pills">
        <x-ui.tabs.tab name="tab1" variant="pills" label="Overview" />
        <x-ui.tabs.tab name="tab2" variant="pills" label="Analytics" />
    </x-ui.tabs.list>
    {{-- Panels --}}
</x-ui.tabs.base>

{{-- Bordered tabs --}}
<x-ui.tabs.base defaultTab="tab1" variant="bordered">
    <x-ui.tabs.list variant="bordered">
        <x-ui.tabs.tab name="tab1" variant="bordered" label="Settings" />
        <x-ui.tabs.tab name="tab2" variant="bordered" label="Profile" />
    </x-ui.tabs.list>
    {{-- Panels --}}
</x-ui.tabs.base>
```

### 5. Tab Content Structure
- **Always wrap tab content** in appropriate containers (cards, forms, etc.)
- **Use consistent spacing** with `space-y-6` or similar
- **Include proper form handling** in tab panels that contain forms
- **Maintain content hierarchy** with proper headings and sections

### 6. Tab Best Practices
- **Default Tab**: Always specify a `defaultTab` to ensure proper initial state
- **Consistent Naming**: Use descriptive, consistent names for tabs (notifications, security, etc.)
- **Icon Standards**: Use `h-4 w-4` for tab icons, place icons in `<x-slot name="icon">`
- **Badge Usage**: Use small badges (`size="sm"`) for counts or status indicators
- **Content Organization**: Group related settings/content logically within tabs

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

### 2. Upload Modal Pattern
For file upload functionality with proper permission checks:

```blade
{{-- Upload button (with permission check) --}}
@if($folder->canUpload(auth()->user()))
    <x-ui.button.primary x-on:click="$dispatch('open-modal', 'upload-files')">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        {{ __('Upload Files') }}
    </x-ui.button.primary>
@endif

{{-- Upload modal definition --}}
@if($folder->canUpload(auth()->user()))
    <x-ui.modal.base name="upload-files" maxWidth="md">
        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">{{ __('Upload Files') }}</h3>
        <form action="{{ route('user.files.upload', $folder) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Select Files') }}
                </label>
                <input type="file" 
                       name="files[]" 
                       multiple 
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300"
                       required>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    {{ __('Supported: PDF, Images, Word, Excel, Text files (Max 10MB each)') }}
                </p>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'upload-files')">
                    {{ __('Cancel') }}
                </x-ui.button.secondary>
                <x-ui.button.primary type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    {{ __('Upload') }}
                </x-ui.button.primary>
            </div>
        </form>
    </x-ui.modal.base>
@endif
```

#### Upload Component Features:
- **Permission-based**: Only shows if user has upload permission (`canUpload()` method)
- **Clean UI**: Uses standard UI components and modal pattern
- **File validation**: Client-side file type and server-side validation
- **Consistent styling**: Matches the UI design system
- **Accessibility**: Proper labels and form structure

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

## Detail View Patterns

### 1. Detail Page Header
Use this pattern for detail/show pages to display entity information with actions:

```blade
{{-- User/Entity Header Card --}}
<x-ui.card.base>
    <x-ui.card.body>
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4">
                <x-ui.avatar name="{{ $entity->name }}" size="lg" />
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $entity->name }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $entity->subtitle }}
                    </p>
                    <div class="mt-2 flex items-center gap-2">
                        {{-- Status badges --}}
                        <x-ui.badge variant="success" size="sm">Active</x-ui.badge>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                {{-- Actions dropdown for secondary actions --}}
                <x-ui.dropdown.base align="right">
                    <x-slot name="trigger">
                        <x-ui.button.secondary size="sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            {{ __('Actions') }}
                        </x-ui.button.secondary>
                    </x-slot>
                    {{-- Dropdown items --}}
                </x-ui.dropdown.base>
                
                {{-- Primary action button --}}
                <x-ui.button.primary size="sm" href="{{ route('...') }}">
                    Primary Action
                </x-ui.button.primary>
            </div>
        </div>
    </x-ui.card.body>
</x-ui.card.base>
```

### 2. Description List Pattern
Use for displaying key-value information in detail views:

```blade
<x-ui.card.base>
    <x-ui.card.header>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
            {{ __('Section Title') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ __('Section description.') }}
        </p>
    </x-ui.card.header>
    <x-ui.card.body>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    {{ __('Field Label') }}
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    Field Value
                </dd>
            </div>
            
            {{-- Field with Badge --}}
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    {{ __('Status') }}
                </dt>
                <dd class="mt-1">
                    <x-ui.badge variant="success">Active</x-ui.badge>
                </dd>
            </div>
            
            {{-- Field with Additional Info --}}
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    {{ __('Created Date') }}
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $date->format('M d, Y') }}
                    <span class="text-gray-500 dark:text-gray-400">
                        ({{ $date->diffForHumans() }})
                    </span>
                </dd>
            </div>
        </dl>
    </x-ui.card.body>
</x-ui.card.base>
```

### 3. Detail Page Layout Pattern
Complete structure for detail/show pages:

```blade
<x-admin.layout 
    title="{{ $entity->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Entities'), 'href' => route('admin.entities.index')],
        ['title' => $entity->name]
    ]"
>
    <div class="space-y-6">
        {{-- Header Section --}}
        <x-ui.card.base>
            {{-- Header content --}}
        </x-ui.card.base>
        
        {{-- Tabs for Different Sections --}}
        <x-ui.tabs.base defaultTab="details">
            <x-ui.tabs.list>
                <x-ui.tabs.tab name="details">Details</x-ui.tabs.tab>
                <x-ui.tabs.tab name="related">Related Items</x-ui.tabs.tab>
            </x-ui.tabs.list>
            
            <x-ui.tabs.panels>
                <x-ui.tabs.panel name="details">
                    {{-- Detail cards --}}
                </x-ui.tabs.panel>
                
                <x-ui.tabs.panel name="related">
                    {{-- Related items table --}}
                </x-ui.tabs.panel>
            </x-ui.tabs.panels>
        </x-ui.tabs.base>
    </div>
</x-admin.layout>
```

### 4. Empty State in Detail Views
For sections with no data:

```blade
<div class="text-center py-8">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {{-- Appropriate icon --}}
    </svg>
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
        {{ __('No items found.') }}
    </p>
</div>
```

## Examples

See these files for reference implementations:
- **Dashboard**: `/resources/views/accountant/dashboard/index.blade.php`
- **Companies List**: `/resources/views/accountant/companies/index.blade.php`
- **User Detail**: `/resources/views/admin/users/show.blade.php`
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