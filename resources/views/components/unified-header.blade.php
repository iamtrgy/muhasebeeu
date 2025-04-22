@props(['description' => null, 'breadcrumbs' => [], 'actions' => null])

<header class="mt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Breadcrumb Navigation -->
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center">
                            <li>
                                @php
                                    $user = auth()->user();
                                    $dashboardRoute = $user && $user->is_admin ? 'admin.dashboard' : ($user && $user->is_accountant ? 'accountant.dashboard' : 'user.dashboard');
                                    $segments = request()->segments();
                                    $currentUrl = '';
                                    $isDashboard = false;
                                    
                                    // Check if we're on a dashboard page
                                    if (count($segments) === 0 || 
                                        (count($segments) === 1 && $segments[0] === 'dashboard') ||
                                        (count($segments) === 2 && $segments[0] === 'admin' && $segments[1] === 'dashboard') ||
                                        (count($segments) === 2 && $segments[0] === 'accountant' && $segments[1] === 'dashboard') ||
                                        (count($segments) === 2 && $segments[0] === 'user' && $segments[1] === 'dashboard')) {
                                        $isDashboard = true;
                                    }
                                @endphp
                                
                                @if($isDashboard)
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Home</span>
                                @else
                                    <a href="{{ route($dashboardRoute) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                        Home
                                    </a>
                                @endif
                            </li>
                    
                    @if(!$isDashboard)
                        @foreach($segments as $i => $segment)
                            @php
                                // Skip user/admin/accountant segments as they're redundant with the user role
                                if (($i === 0 && ($segment === 'user' || $segment === 'admin' || $segment === 'accountant'))) {
                                    continue;
                                }
                                
                                // Don't build URL incrementally for folders - we'll handle that specially
                                if ($segment !== 'folders') {
                                    $currentUrl .= '/' . $segment;
                                }
                                $segmentName = ucfirst(str_replace('-', ' ', $segment));
                                
                                // Special cases for segment names
                                if ($segment === 'tax-calendar') {
                                    $segmentName = 'Tax Calendar';
                                }
                                
                                // Handle folder names in breadcrumbs
                                if ($segment === 'folders' && $i < count($segments) - 1) {
                                    $segmentName = 'Folders';
                                    
                                    // Determine the role from the URL path
                                    $role = '';
                                    if ($i > 0 && isset($segments[$i-1])) {
                                        $role = $segments[$i-1];
                                    }
                                    
                                    // Set the correct folders URL based on role
                                    if ($role === 'user') {
                                        $currentUrl = url('/user/folders');
                                    } elseif ($role === 'admin') {
                                        $currentUrl = url('/admin/folders');
                                    } elseif ($role === 'accountant') {
                                        $currentUrl = url('/accountant/folders');
                                    } else {
                                        // Fallback to user folders if role can't be determined
                                        $currentUrl = url('/user/folders');
                                    }
                                }
                                
                                // If this is a folder ID (numeric value after 'folders' segment)
                                if (is_numeric($segment) && $i > 0 && $segments[$i-1] === 'folders') {
                                    // Get the folder name from the database
                                    $folder = \App\Models\Folder::find($segment);
                                    if ($folder) {
                                        $segmentName = $folder->name;
                                        
                                        // If this folder has a parent, we need to show the hierarchy
                                        if ($folder->parent_id && !isset($showedParents)) {
                                            $ancestors = collect([]);
                                            $currentFolder = $folder->parent;
                                            
                                            // Build the ancestor chain
                                            while ($currentFolder) {
                                                $ancestors->push($currentFolder);
                                                $currentFolder = $currentFolder->parent;
                                            }
                                            
                                            // Display ancestors in reverse order (root first)
                                            foreach ($ancestors->reverse() as $ancestor) {
                                                $ancestorUrl = url('/user/folders/' . $ancestor->id);
                                                echo '<li class="flex items-center">'
                                                    . '<span class="mx-2 text-gray-400">/</span>'
                                                    . '<a href="' . $ancestorUrl . '" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">'
                                                    . e($ancestor->name)
                                                    . '</a>'
                                                    . '</li>';
                                            }
                                            
                                            $showedParents = true;
                                        }
                                    }
                                }
                            @endphp
                        
                            <li class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                @if($i < count($segments) - 1)
                                    <a href="{{ $currentUrl }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                        {{ $segmentName }}
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $segmentName }}</span>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ol>
                </nav>
                
                <!-- Actions Section -->
                @if(isset($actions))
                    <div class="flex space-x-2">
                        {{ $actions }}
                    </div>
                @endif
            </div>
            
            <!-- Description if provided -->
            @if($description)
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @endif
            
            <!-- Custom slot content if provided -->
            @if(!$slot->isEmpty())
                <div class="mt-2">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</header>
