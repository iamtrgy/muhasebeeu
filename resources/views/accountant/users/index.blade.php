<x-accountant.layout 
    title="Users" 
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Users')]
    ]"
>
    <div class="space-y-6">
        <!-- Users Card -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Users') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Users assigned to your account') }} ({{ $users->count() }} users)</p>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($users->count() > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('User') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Email') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Companies') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Last Login') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <x-ui.avatar name="{{ $user->name }}" size="md" class="flex-shrink-0" />
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ __('ID') }}: {{ $user->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('Verified') }}: {{ $user->email_verified_at ? __('Yes') : __('No') }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($user->companies->count() > 0)
                                            <x-ui.badge variant="success">
                                                {{ $user->companies->count() }} {{ __('Companies') }}
                                            </x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary">
                                                {{ __('No Companies') }}
                                            </x-ui.badge>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($user->last_login_at)
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $user->last_login_at->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $user->last_login_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Never') }}</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <a href="{{ route('accountant.users.show', $user) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('View details') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if($user->companies->count() > 0)
                                            <a href="{{ route('accountant.users.show', $user) }}" 
                                               class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                               title="{{ __('View files') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                @else
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Users Assigned') }}</x-slot>
                        <x-slot name="description">{{ __('You do not have any users assigned to your account yet. Contact your administrator to assign users to your account.') }}</x-slot>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Pagination -->
        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
            <x-ui.card.base>
                <x-ui.card.body class="px-6 py-4">
                    {{ $users->links() }}
                </x-ui.card.body>
            </x-ui.card.base>
        @endif
    </div>
</x-accountant.layout> 