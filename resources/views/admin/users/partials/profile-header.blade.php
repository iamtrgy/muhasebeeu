<!-- User Profile Header -->
<x-ui.card.base class="mb-6">
    <x-ui.card.body>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                    <span class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    <div class="flex items-center mt-1 space-x-2">
                        @if($user->is_admin)
                            <x-ui.badge variant="primary">{{ __('Admin') }}</x-ui.badge>
                        @endif
                        @if($user->email_verified_at)
                            <x-ui.badge variant="success">{{ __('Verified') }}</x-ui.badge>
                        @else
                            <x-ui.badge variant="warning">{{ __('Not Verified') }}</x-ui.badge>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2">
                <!-- Action Buttons -->
                <x-ui.button.primary 
                    type="button" 
                    onclick="Livewire.dispatch('openModal', { component: 'admin.send-email-modal', arguments: { userId: {{ $user->id }} }})"
                >
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                    {{ __('Email User') }}
                </x-ui.button.primary>
                
                <x-ui.button.primary href="{{ route('admin.users.subscription.manage', $user) }}">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Manage Subscription') }}
                </x-ui.button.primary>
                
                @if(!$user->email_verified_at)
                <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="inline">
                    @csrf
                    <x-ui.button.primary type="submit" class="bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Verify Email') }}
                    </x-ui.button.primary>
                </form>
                @endif
            </div>
        </div>
    </x-ui.card.body>
</x-ui.card.base>
