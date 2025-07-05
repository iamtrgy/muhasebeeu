<!-- User Info Card -->
<x-ui.card.base class="mb-6">
    <x-ui.card.header>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('User Information') }}</h3>
    </x-ui.card.header>
    <x-ui.card.body>
    <!-- User Role Actions -->
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')
            
            <!-- Accountant Toggle -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" name="is_accountant" id="is_accountant" 
                        {{ $user->is_accountant ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <label for="is_accountant" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Accountant Role') }}</label>
                </div>
                <x-ui.button.primary type="submit" size="sm">
                    {{ __('Save Role') }}
                </x-ui.button.primary>
            </div>
        </form>

        @if($user->is_accountant)
            <!-- Assign Users Button -->
            <div class="mt-4">
                <x-ui.button.primary 
                    href="{{ route('admin.users.assign', $user) }}" 
                    class="w-full justify-center bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500"
                >
                    {{ __('Assign Users to Accountant') }}
                </x-ui.button.primary>
            </div>
            
            <!-- Assign Companies Button -->
            <div class="mt-3">
                <x-ui.button.primary 
                    href="{{ route('admin.users.assign-companies', $user) }}" 
                    class="w-full justify-center"
                >
                    {{ __('Assign Companies to Accountant') }}
                </x-ui.button.primary>
            </div>
        @endif
    </div>

    <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Name') }}</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
        </div>
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
        </div>
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('User ID') }}</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->id }}</dd>
        </div>
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Member Since') }}</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $user->created_at->format('M d, Y') }}
                <span class="text-gray-500 dark:text-gray-400 text-xs">({{ $user->created_at->diffForHumans() }})</span>
            </dd>
        </div>
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $user->updated_at->format('M d, Y') }}
                <span class="text-gray-500 dark:text-gray-400 text-xs">({{ $user->updated_at->diffForHumans() }})</span>
            </dd>
        </div>
        @if($user->email_verified_at)
        <div class="pb-4">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email Verified') }}</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $user->email_verified_at->format('M d, Y') }}
                <span class="text-gray-500 dark:text-gray-400 text-xs">({{ $user->email_verified_at->diffForHumans() }})</span>
            </dd>
        </div>
        @endif
    </dl>
    </x-ui.card.body>
</x-ui.card.base>
