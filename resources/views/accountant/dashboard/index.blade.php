<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                        <span class="text-gray-900 dark:text-gray-100">{{ __('Dashboard') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Users Count Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    {{ __('Assigned Users') }}
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ $usersCount }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('accountant.users.index') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                                {{ __('View all users') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Companies Count Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    {{ __('Assigned Companies') }}
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ $companiesCount }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('accountant.companies.index') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                                {{ __('View all companies') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Users -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Recent Users') }}</h3>
                        
                        @if($recentUsers->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentUsers as $user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                        <div class="ml-auto">
                                            <a href="{{ route('accountant.users.show', $user->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ __('View') }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No users assigned yet.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Companies -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Recent Companies') }}</h3>
                        
                        @if($recentCompanies->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentCompanies as $company)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ substr($company->name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $company->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->user->name }}</div>
                                        </div>
                                        <div class="ml-auto">
                                            <a href="{{ route('accountant.companies.show', $company->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 dark:text-green-200 dark:bg-green-900 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                {{ __('View') }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No companies assigned yet.') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Files Card -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Recent Files') }}</h3>
                    
                    @if($recentFiles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('File Name') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('Folder') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('Company') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">{{ __('Actions') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentFiles as $file)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <x-icons name="file" class="h-5 w-5 text-blue-500" />
                                                    </div>
                                                    <div class="ml-4">
                                                        @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 cursor-pointer" onclick="previewFile('{{ $file->original_name }}', '{{ $file->mime_type }}', '{{ route('accountant.files.preview', $file) }}')">
                                                                {{ $file->original_name }}
                                                            </div>
                                                        @else
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $file->original_name }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    @if($file->folder)
                                                        <a href="{{ route('accountant.users.viewFolder', ['userId' => $file->folder->created_by, 'folderId' => $file->folder->id]) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $file->folder->name }}
                                                        </a>
                                                    @else
                                                        {{ __('(No folder)') }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    @if($file->folder && $file->folder->creator && $file->folder->creator->companies->isNotEmpty())
                                                        @php $company = $file->folder->creator->companies->first(); @endphp
                                                        <a href="{{ route('accountant.companies.show', $company->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $company->name }}
                                                        </a>
                                                    @elseif($file->uploader && $file->uploader->companies->isNotEmpty())
                                                        @php $company = $file->uploader->companies->first(); @endphp
                                                        <a href="{{ route('accountant.companies.show', $company->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $company->name }}
                                                        </a>
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $file->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $file->created_at->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('accountant.files.download', $file) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="{{ __('Download') }}">
                                                    <x-icons name="download" />
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">{{ __('No files found from your assigned users.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <x-folder.file-preview-modal />
</x-app-layout>