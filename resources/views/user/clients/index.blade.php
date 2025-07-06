<x-user.layout 
    title="{{ __('Clients') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Clients')]
    ]"
>
    <div class="space-y-6">
        @if(session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
        @endif

        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Clients') }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your client list and contact information') }}</p>
                        </div>
                    </div>
                    <x-ui.button.primary href="{{ route('user.clients.create') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('New Client') }}
                    </x-ui.button.primary>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                @if(count($clients) > 0)
                    <x-ui.table.base>
                        <x-ui.table.header>
                            <x-ui.table.head-cell>{{ __('Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Email') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Phone') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('VAT Number') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell class="text-right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-ui.table.header>
                        <x-ui.table.body>
                            @foreach($clients as $client)
                                <x-ui.table.row>
                                    <x-ui.table.cell class="font-medium">{{ $client->name }}</x-ui.table.cell>
                                    <x-ui.table.cell>{{ $client->email ?: '-' }}</x-ui.table.cell>
                                    <x-ui.table.cell>{{ $client->phone ?: '-' }}</x-ui.table.cell>
                                    <x-ui.table.cell>{{ $client->vat_number ?: '-' }}</x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <a href="{{ route('user.clients.edit', $client) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Edit') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('user.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this client?') }}');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1 rounded-lg text-gray-400 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                    title="{{ __('Delete') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </x-ui.table.action-cell>
                                </x-ui.table.row>
                            @endforeach
                        </x-ui.table.body>
                    </x-ui.table.base>
                    
                    @if($clients->hasPages())
                        <div class="mt-6">
                            {{ $clients->links() }}
                        </div>
                    @endif
                @else
                    <x-ui.table.empty-state>
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('No clients yet') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Get started by creating your first client.') }}</p>
                        <div class="mt-6">
                            <x-ui.button.primary href="{{ route('user.clients.create') }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Create New Client') }}
                            </x-ui.button.primary>
                        </div>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-user.layout>
