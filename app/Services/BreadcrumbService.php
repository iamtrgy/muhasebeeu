<?php

namespace App\Services;

class BreadcrumbService
{
    /**
     * Generate breadcrumbs based on route name and parameters
     * This is a generic method that can be used by the base layout
     *
     * @param string $routeName
     * @param array $routeParams
     * @return array
     */
    public static function generateBreadcrumbs($routeName, $routeParams = [])
    {
        // Extract the method name from the route
        $segments = explode('.', $routeName);
        $methodName = '';
        
        // Handle different route patterns
        if (count($segments) === 1) {
            // For single segment routes like 'profile', 'dashboard'
            $methodName = $segments[0];
        } 
        else if (count($segments) === 2) {
            // For routes like 'admin.dashboard', 'admin.settings'
            $area = $segments[0]; // admin, user, accountant
            $action = $segments[1]; // dashboard, settings
            
            // Format method name like adminDashboard
            $methodName = $area . ucfirst($action);
        }
        else if (count($segments) >= 3) {
            // For routes like 'admin.users.index', 'user.folders.show'
            $area = $segments[0]; // admin, user, accountant
            $resource = $segments[1]; // users, folders, companies
            $action = $segments[2]; // index, show, edit
            
            // Format the method name based on common patterns
            if ($action === 'index') {
                $methodName = $area . ucfirst($resource);
            } 
            else if (in_array($action, ['show', 'edit', 'update'])) {
                $singularResource = \Illuminate\Support\Str::singular($resource);
                $methodName = $area . ucfirst($singularResource) . 'Detail';
                
                // Check if the method exists
                if (!method_exists(self::class, $methodName)) {
                    // Fallback to the resource list if detail method doesn't exist
                    $methodName = $area . ucfirst($resource);
                }
            } 
            else {
                // For custom actions, try to find a specific method
                $singularResource = \Illuminate\Support\Str::singular($resource);
                $customMethodName = $area . ucfirst($singularResource) . ucfirst($action);
                if (method_exists(self::class, $customMethodName)) {
                    $methodName = $customMethodName;
                } else {
                    // Fallback to the resource list
                    $methodName = $area . ucfirst($resource);
                }
            }
        }
        
        // Call the breadcrumb method if it exists
        if (!empty($methodName) && method_exists(self::class, $methodName)) {
            // Find model parameters if any
            $modelParam = null;
            foreach ($routeParams as $param) {
                if (is_object($param)) {
                    $modelParam = $param;
                    break;
                }
            }
            
            if ($modelParam) {
                return self::{$methodName}($modelParam);
            } else {
                return self::{$methodName}();
            }
        }
        
        // Default empty breadcrumbs if no method found
        return [];
    }
    /**
     * Generate breadcrumbs for user dashboard
     *
     * @return array
     */
    public static function dashboard()
    {
        return [
            ['title' => 'Dashboard', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for profile edit page
     *
     * @return array
     */
    public static function profileEdit()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Settings', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for company index page
     *
     * @return array
     */
    public static function companyIndex()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Companies', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for company show page
     *
     * @return array
     */
    public static function companyShow()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Companies', 'url' => route('user.companies.index')],
            ['title' => 'Company Details', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for company edit page
     *
     * @return array
     */
    public static function companyEdit()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Companies', 'url' => route('user.companies.index')],
            ['title' => 'Edit Company', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for folders index page
     *
     * @return array
     */
    public static function foldersIndex()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Folders', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for folder show page
     *
     * @return array
     */
    public static function foldersShow()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Folders', 'url' => route('user.folders.index')],
            ['title' => 'Folder Details', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for folder create page
     *
     * @return array
     */
    public static function foldersCreate()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Folders', 'url' => route('user.folders.index')],
            ['title' => 'Create Folder', 'current' => true]
        ];
    }
    /**
     * Generate breadcrumbs for admin dashboard
     *
     * @return array
     */
    public static function adminDashboard()
    {
        return [
            ['title' => 'Admin', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for user list page
     *
     * @return array
     */
    public static function adminUsers()
    {
        return [
            ['title' => 'Users', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for user detail page
     *
     * @param \App\Models\User $user
     * @return array
     */
    public static function adminUserDetail($user)
    {
        return [
            ['title' => 'Users', 'url' => route('admin.users.index')],
            ['title' => $user->name, 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for user subscription page
     *
     * @param \App\Models\User $user
     * @return array
     */
    public static function adminUserSubscription($user)
    {
        return [
            ['title' => 'Users', 'url' => route('admin.users.index')],
            ['title' => $user->name, 'url' => route('admin.users.show', $user)],
            ['title' => 'Subscription', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for folders list page
     *
     * @return array
     */
    public static function adminFolders()
    {
        return [
            ['title' => 'Folders', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for folder detail page
     *
     * @param \App\Models\Folder $folder
     * @return array
     */
    public static function adminFolderDetail($folder)
    {
        $breadcrumbs = [
            ['title' => 'Folders', 'url' => route('admin.folders.index')]
        ];

        // Add parent folders if any
        $ancestors = collect([]);
        $current = $folder;
        
        while($current->parent) {
            $ancestors->prepend($current->parent);
            $current = $current->parent;
        }

        foreach($ancestors as $ancestor) {
            $breadcrumbs[] = [
                'title' => $ancestor->name,
                'url' => route('admin.folders.show', $ancestor)
            ];
        }

        // Add current folder
        $breadcrumbs[] = ['title' => $folder->name, 'current' => true];

        return $breadcrumbs;
    }

    /**
     * Generate breadcrumbs for companies list page
     *
     * @return array
     */
    public static function adminCompanies()
    {
        return [
            ['title' => 'Companies', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for company detail page
     *
     * @param \App\Models\Company $company
     * @return array
     */
    public static function adminCompanyDetail($company)
    {
        return [
            ['title' => 'Companies', 'url' => route('admin.companies.index')],
            ['title' => $company->name, 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for admin settings page
     *
     * @return array
     */
    public static function adminSettings()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Settings', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for duplicate companies page
     *
     * @return array
     */
    public static function adminDuplicateCompanies()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Companies', 'url' => route('admin.companies.index')],
            ['title' => 'Duplicates', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for folder create page
     *
     * @return array
     */
    public static function adminFolderCreate()
    {
        return [
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Folders', 'url' => route('admin.folders.index')],
            ['title' => 'Create', 'current' => true]
        ];
    }

    /**
     * Generate breadcrumbs for folder edit page
     *
     * @param \App\Models\Folder $folder
     * @return array
     */
    public static function adminFolderEdit($folder)
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Folders', 'url' => route('admin.folders.index')],
            ['title' => $folder->name, 'url' => route('admin.folders.show', $folder)],
            ['title' => 'Edit', 'current' => true]
        ];

        return $breadcrumbs;
    }

    /**
     * Generate breadcrumbs for subscription debug page
     *
     * @param \App\Models\User $user
     * @return array
     */
    public static function adminUserSubscriptionDebug($user)
    {
        return [
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Users', 'url' => route('admin.users.index')],
            ['title' => $user->name, 'url' => route('admin.users.show', $user)],
            ['title' => 'Subscription', 'url' => route('admin.users.subscription.manage', $user)],
            ['title' => 'Debug', 'current' => true]
        ];
    }
}
