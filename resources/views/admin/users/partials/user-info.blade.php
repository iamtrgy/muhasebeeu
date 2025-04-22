<!-- User Info Card -->
<x-cards.info-card title="{{ __('User Information') }}">
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
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >
                    <label for="is_accountant" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Accountant Role') }}</label>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Save Role') }}
                </button>
            </div>
        </form>

        @if($user->is_accountant)
            <!-- Assign Users Button -->
            <div class="mt-4">
                <a href="{{ route('admin.users.assign', $user) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full justify-center">
                    {{ __('Assign Users to Accountant') }}
                </a>
            </div>
            
            <!-- Assign Companies Button -->
            <div class="mt-3">
                <a href="{{ route('admin.users.assign-companies', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full justify-center">
                    {{ __('Assign Companies to Accountant') }}
                </a>
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
</x-cards.info-card>
