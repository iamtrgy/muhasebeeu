@props(['company' => null])

<nav class="flex min-w-full" aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-2">
        <li>
            <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                Home
            </a>
        </li>
        @if(auth()->user()->companies->count() > 1)
            <li class="text-gray-400">/</li>
            <li>
                <a href="{{ route('user.companies.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                    Companies
                </a>
            </li>
        @endif
        @if($company)
            <li class="text-gray-400">/</li>
            <li>
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $company->name }}</span>
            </li>
        @endif
    </ol>
</nav>
