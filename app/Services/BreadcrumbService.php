<?php

namespace App\Services;

class BreadcrumbService
{
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
