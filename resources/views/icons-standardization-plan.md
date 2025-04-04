# Icon Standardization Plan

## Problem Statement
Currently, the project has inconsistent icon usage across different sections (admin, accountant, user). This creates a disjointed user experience and makes maintenance more difficult.

## Solution
We have created a centralized icon component (`resources/views/components/icons.blade.php`) that provides a single source of truth for all icons used in the application. This component:

1. Standardizes the icon catalog
2. Provides consistent sizing and styling
3. Makes future updates easier (change in one place)
4. Improves maintainability and DX

## Implementation Plan

### 1. Current Progress
We've already standardized the accountant section with these changes:
- Updated accountant/users/index.blade.php
- Updated accountant/users/show.blade.php
- Updated accountant/users/folder.blade.php

### 2. Next Steps: Admin Section
Need to standardize admin views:

#### Admin Folders
- Replace search icon with `<x-icons name="search" />`
- Replace add icon with `<x-icons name="add" />`
- Replace folder icon with `<x-icons name="folder" />`
- Replace view icon with `<x-icons name="view" />`
- Replace edit icon with `<x-icons name="edit" />`
- Replace delete icon with `<x-icons name="delete" />`

#### Admin Companies
- Replace search icon with `<x-icons name="search" />`
- Replace add icon with `<x-icons name="add" />`
- Replace duplicate icon with appropriate standardized icon
- Replace view icon with `<x-icons name="view" />`

#### Admin Users
- Replace search icon with `<x-icons name="search" />`
- Replace view icon with `<x-icons name="view" />`
- Replace edit icon with `<x-icons name="edit" />`
- Replace more/menu icon with `<x-icons name="more" />`

### 3. Update Shared Components
- Update any icon usages in shared components

### 4. Update User-Facing Views
- Apply the same standardization to end-user views

### 5. Documentation
- Add documentation about the icon component and how to use it
- Include a showcase of all available icons

## Benefits

1. **Consistent User Experience**: Users see the same icons for the same actions across the entire platform
2. **Improved Maintainability**: Updates to icons only need to be made in one place
3. **Better Developer Experience**: Developers can easily find and use the right icons
4. **Smaller Bundle Size**: Avoid duplicating SVG paths
5. **Accessibility**: Consistent icons improve familiarity and recognition for users

## Usage Examples

```blade
<!-- Basic usage -->
<x-icons name="view" />

<!-- With custom sizing -->
<x-icons name="edit" class="w-6 h-6" />

<!-- With custom color -->
<x-icons name="delete" class="text-red-500" />
```

## Specific Icon Recommendations

| Action | Icon Name | Usage Context |
|--------|-----------|---------------|
| Search | search | Search inputs |
| View | view | View details links |
| Edit | edit | Edit item links |
| Delete | delete | Delete buttons |
| Add | add | Add new item buttons |
| Download | download | Download buttons |
| Upload | upload | Upload buttons |
| File | file | File representations |
| Folder | folder | Folder representations |
| User | user | User representations |
| Company | company | Company representations |
| Back | back | Back buttons |
| More | more | More actions menus | 