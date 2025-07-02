# UI Standards & Design System

## Table of Contents
1. [Color System](#color-system)
2. [Typography](#typography)
3. [Spacing & Layout](#spacing--layout)
4. [Components](#components)
5. [Forms](#forms)
6. [Icons & Images](#icons--images)
7. [Responsive Design](#responsive-design)
8. [Dark Mode](#dark-mode)
9. [Accessibility](#accessibility)
10. [Best Practices](#best-practices)

---

## Color System

### Primary Palette
```css
/* Brand Colors */
--color-primary: #6366f1;        /* indigo-500 */
--color-primary-hover: #4f46e5;  /* indigo-600 */
--color-primary-focus: #4338ca;  /* indigo-700 */

/* Neutral Colors */
--color-text: #111827;           /* gray-900 */
--color-text-muted: #6b7280;     /* gray-500 */
--color-background: #ffffff;      /* white */
--color-surface: #f9fafb;        /* gray-50 */
--color-border: #e5e7eb;         /* gray-200 */
```

### Semantic Colors
```css
/* Status Colors */
--color-success: #10b981;        /* emerald-500 */
--color-warning: #f59e0b;        /* amber-500 */
--color-error: #ef4444;          /* red-500 */
--color-info: #3b82f6;           /* blue-500 */

/* Dark Mode Variants */
--color-dark-text: #f9fafb;      /* gray-50 */
--color-dark-background: #111827; /* gray-900 */
--color-dark-surface: #1f2937;   /* gray-800 */
--color-dark-border: #374151;    /* gray-700 */
```

### Usage Guidelines
- **Primary**: Use for primary actions, links, and brand elements
- **Success**: Positive actions, confirmations, success states
- **Warning**: Warnings, cautions, pending states
- **Error**: Errors, destructive actions, failed states
- **Info**: Informational messages, help text

---

## Typography

### Font Stack
```css
font-family: 'Figtree', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
```

### Type Scale
| Class | Size | Use Case |
|-------|------|----------|
| `text-xs` | 0.75rem | Labels, help text, timestamps |
| `text-sm` | 0.875rem | Body text, form inputs |
| `text-base` | 1rem | Default text, paragraphs |
| `text-lg` | 1.125rem | Section headers, card titles |
| `text-xl` | 1.25rem | Page sub-headers |
| `text-2xl` | 1.5rem | Page headers |
| `text-3xl` | 1.875rem | Main titles |

### Font Weights
- **Normal** (`font-normal`): Body text
- **Medium** (`font-medium`): Labels, emphasis
- **Semibold** (`font-semibold`): Headers, buttons
- **Bold** (`font-bold`): Important text, CTAs

### Text Colors
```html
<!-- Primary text -->
<p class="text-gray-900 dark:text-gray-100">Main content</p>

<!-- Secondary text -->
<p class="text-gray-600 dark:text-gray-400">Supporting content</p>

<!-- Muted text -->
<p class="text-gray-500 dark:text-gray-500">Help text</p>
```

---

## Spacing & Layout

### Spacing Scale
Use consistent spacing units based on 4px/0.25rem increments:

| Class | Space | Pixels | Use Case |
|-------|-------|--------|----------|
| `p-1` | 0.25rem | 4px | Tight spacing |
| `p-2` | 0.5rem | 8px | Small elements |
| `p-4` | 1rem | 16px | Default padding |
| `p-6` | 1.5rem | 24px | Card/section padding |
| `p-8` | 2rem | 32px | Large sections |

### Container Widths
```html
<!-- Full width with max constraint -->
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Content -->
</div>

<!-- Content sections -->
<div class="max-w-3xl">  <!-- For text content -->
<div class="max-w-4xl">  <!-- For forms -->
<div class="max-w-6xl">  <!-- For dashboards -->
```

### Grid System
```html
<!-- Responsive grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <!-- Grid items -->
</div>

<!-- Common patterns -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">  <!-- Two column -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">  <!-- Stats grid -->
```

---

## Components

### Buttons

#### Primary Button
```html
<button class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
  Primary Action
</button>
```

#### Secondary Button
```html
<button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
  Secondary Action
</button>
```

#### Danger Button
```html
<button class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
  Delete
</button>
```

### Cards
```html
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
  <div class="p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
      Card Title
    </h3>
    <p class="text-gray-600 dark:text-gray-400">
      Card content goes here...
    </p>
  </div>
</div>
```

### Badges
```html
<!-- Status badges -->
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
  Active
</span>

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
  Pending
</span>

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
  Inactive
</span>
```

### Tables
```html
<div class="overflow-x-auto">
  <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-700">
      <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
          Column Header
        </th>
      </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
          Cell content
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

---

## Forms

### Input Fields
```html
<div>
  <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
    Email Address <span class="text-red-500">*</span>
  </label>
  <input type="email" 
         id="email" 
         name="email" 
         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
  <p class="mt-1 text-sm text-gray-500">We'll never share your email.</p>
</div>
```

### Select Dropdowns
```html
<select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-900 dark:border-gray-700">
  <option>Select an option</option>
  <option>Option 1</option>
  <option>Option 2</option>
</select>
```

### Checkboxes & Radios
```html
<label class="inline-flex items-center">
  <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
  <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Remember me</span>
</label>
```

### Form Validation
```html
<!-- Error state -->
<input type="text" class="... border-red-300 focus:border-red-500 focus:ring-red-500">
<p class="mt-1 text-sm text-red-600">This field is required.</p>

<!-- Success state -->
<input type="text" class="... border-green-300 focus:border-green-500 focus:ring-green-500">
<p class="mt-1 text-sm text-green-600">Looks good!</p>
```

---

## Icons & Images

### Icon Guidelines
- Use consistent icon sizes: `h-4 w-4` (16px), `h-5 w-5` (20px), `h-6 w-6` (24px)
- Maintain consistent stroke width
- Use currentColor for dynamic coloring

```html
<!-- Icon example -->
<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="..."></path>
</svg>
```

### Image Handling
```html
<!-- Responsive images -->
<img src="..." alt="Description" class="w-full h-auto rounded-lg shadow-sm">

<!-- Avatar -->
<img src="..." alt="User" class="h-10 w-10 rounded-full">
```

---

## Responsive Design

### Breakpoints
| Breakpoint | Min Width | CSS Class |
|------------|-----------|-----------|
| Mobile | 0px | (default) |
| Small | 640px | `sm:` |
| Medium | 768px | `md:` |
| Large | 1024px | `lg:` |
| Extra Large | 1280px | `xl:` |

### Mobile-First Approach
```html
<!-- Stack on mobile, side-by-side on desktop -->
<div class="flex flex-col md:flex-row gap-4">
  <div class="flex-1">Content 1</div>
  <div class="flex-1">Content 2</div>
</div>
```

---

## Dark Mode

### Implementation
Always provide dark mode variants for colors:

```html
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
  <p class="text-gray-600 dark:text-gray-400">
    Supporting text
  </p>
</div>
```

### Color Mappings
| Light Mode | Dark Mode |
|------------|-----------|
| `bg-white` | `dark:bg-gray-800` |
| `bg-gray-50` | `dark:bg-gray-900` |
| `text-gray-900` | `dark:text-gray-100` |
| `text-gray-600` | `dark:text-gray-400` |
| `border-gray-200` | `dark:border-gray-700` |

---

## Accessibility

### Focus States
All interactive elements must have visible focus states:
```css
focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
```

### ARIA Labels
```html
<button aria-label="Delete item">
  <svg aria-hidden="true">...</svg>
</button>
```

### Color Contrast
- Normal text: 4.5:1 contrast ratio
- Large text: 3:1 contrast ratio
- Use tools to verify contrast ratios

### Keyboard Navigation
- All interactive elements must be keyboard accessible
- Use semantic HTML elements
- Provide skip links for navigation

---

## Best Practices

### Do's
- ✅ Use consistent spacing from the scale
- ✅ Maintain color consistency across similar elements
- ✅ Always include dark mode variants
- ✅ Use semantic HTML elements
- ✅ Test on multiple screen sizes
- ✅ Provide meaningful alt text for images
- ✅ Use focus states for all interactive elements

### Don'ts
- ❌ Mix different shades for the same semantic meaning
- ❌ Use inline styles for common patterns
- ❌ Forget hover and focus states
- ❌ Use color alone to convey information
- ❌ Create new spacing values outside the scale
- ❌ Mix different button styles in the same context

### Component Creation Checklist
When creating new components:
- [ ] Follow the color system
- [ ] Use consistent spacing
- [ ] Include all interactive states (hover, focus, active)
- [ ] Add dark mode support
- [ ] Test responsiveness
- [ ] Verify accessibility
- [ ] Document usage examples

---

## Implementation Examples

### Page Layout
```html
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
  <!-- Navigation -->
  <nav class="bg-white dark:bg-gray-800 shadow">
    <!-- Nav content -->
  </nav>
  
  <!-- Main content -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
      Page Title
    </h1>
    
    <!-- Content sections -->
  </main>
</div>
```

### Form Section
```html
<form class="space-y-6">
  <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
      Section Title
    </h2>
    
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <!-- Form fields -->
    </div>
    
    <div class="mt-6 flex items-center justify-end space-x-3">
      <button type="button" class="[secondary button classes]">
        Cancel
      </button>
      <button type="submit" class="[primary button classes]">
        Save Changes
      </button>
    </div>
  </div>
</form>
```

---

This UI Standards document should be treated as a living document and updated as the design system evolves.