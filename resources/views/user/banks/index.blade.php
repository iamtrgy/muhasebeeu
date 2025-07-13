<x-user.layout 
    title="" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard')],
        ['title' => __('Banks'), 'active' => true]
    ]"
>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ __('Bank Statements') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Manage your monthly bank statements for') }} {{ $company->name }}
                        </p>
                    </div>
                    @if($selectedMonthFolder)
                        <x-ui.button.primary 
                            href="{{ route('user.folders.show', $selectedMonthFolder) }}"
                            target="_blank"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            {{ __('Upload Statement') }}
                        </x-ui.button.primary>
                    @endif
                </div>
            </div>
        </div>

        <!-- Year/Month Selector -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <!-- Year Selector -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">{{ __('Year:') }}</label>
                        <select 
                            onchange="window.location.href='{{ route('user.banks.index') }}?year=' + this.value + '&month={{ $selectedMonth }}'"
                            class="px-3 py-1 pr-8 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white appearance-none bg-white dark:bg-gray-700 bg-no-repeat bg-right bg-[length:16px_16px] bg-[position:right_8px_center]"
                            style="background-image: url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3e%3cpath stroke=%27%236b7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27M6 8l4 4 4-4%27/%3e%3c/svg%3e');"
                        >
                            @foreach($years as $year)
                                <option value="{{ $year->name }}" {{ $year->name == $selectedYear ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month Tabs -->
                    <div class="flex-1 flex space-x-1 overflow-x-auto">
                        @php
                            $monthNames = [
                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                                9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
                            ];
                        @endphp
                        @for($m = 1; $m <= 12; $m++)
                            @php
                                $monthFolder = $months->firstWhere('name', Carbon\Carbon::createFromDate(null, $m, 1)->format('F'));
                                $hasFiles = $monthFolder && $monthFolder->files()->count() > 0;
                            @endphp
                            <a 
                                href="{{ route('user.banks.index', ['year' => $selectedYear, 'month' => $m]) }}"
                                class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                                    {{ $m == $selectedMonth 
                                        ? 'bg-indigo-600 text-white' 
                                        : ($hasFiles 
                                            ? 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' 
                                            : 'text-gray-400 dark:text-gray-500') 
                                    }}"
                            >
                                {{ $monthNames[$m] }}
                                @if($hasFiles)
                                    <span class="ml-1 text-xs">•</span>
                                @endif
                            </a>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Monthly Statements List -->
            <div class="lg:col-span-2">
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Statements') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if($files->count() > 0)
                            <div class="space-y-3">
                                @foreach($files as $file)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $file->original_name }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ __('Uploaded') }} {{ $file->created_at->diffForHumans() }}
                                                    @if($file->size) • {{ number_format($file->size / 1024 / 1024, 2) }} MB @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('user.files.preview', $file) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                               title="{{ __('Preview') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('user.files.download', $file) }}" 
                                               class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                                               title="{{ __('Download') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <x-ui.table.empty-state>
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No bank statements for this month.') }}
                                </p>
                                @if($selectedMonthFolder)
                                    <x-ui.button.primary 
                                        href="{{ route('user.folders.show', $selectedMonthFolder) }}"
                                        target="_blank"
                                        size="sm"
                                        class="mt-4"
                                    >
                                        {{ __('Upload Statement') }}
                                    </x-ui.button.primary>
                                @endif
                            </x-ui.table.empty-state>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Monthly Summary -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Monthly Summary') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('Statements') }}
                                </dt>
                                <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $files->count() }}
                                </dd>
                            </div>
                            @if($files->count() > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Last Upload') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $files->first()->created_at->format('M d, Y') }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </x-ui.card.body>
                </x-ui.card.base>

                <!-- Recent Statements -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Recent Statements') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if($recentStatements->count() > 0)
                            <ul class="space-y-3">
                                @foreach($recentStatements->take(5) as $statement)
                                    <li>
                                        <a href="{{ route('user.files.preview', $statement) }}" 
                                           class="block hover:bg-gray-50 dark:hover:bg-gray-700 -mx-2 px-2 py-1 rounded">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $statement->original_name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $statement->folder->parent->name ?? '' }} {{ $statement->folder->name ?? '' }}
                                            </p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('No statements uploaded yet.') }}
                            </p>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>
        </div>
    </div>
</x-user.layout>