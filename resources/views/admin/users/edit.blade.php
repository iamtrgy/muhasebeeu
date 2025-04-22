<x-admin-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="userEditForm">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- User Roles -->
                        <div class="mb-4">
                            <x-input-label :value="__('User Roles')" />
                            <div class="mt-2 space-y-3">
                                <div class="flex items-center">
                                    <input id="is_admin" name="is_admin" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ $user->is_admin ? 'checked' : '' }}>
                                    <label for="is_admin" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                        {{ __('Administrator') }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('(Full system access)') }}</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="is_accountant" name="is_accountant" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ $user->is_accountant ? 'checked' : '' }}>
                                    <label for="is_accountant" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                        {{ __('Accountant') }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('(Can access assigned users and companies)') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Leave blank to keep the current password') }}
                            </p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Update User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userEditForm');
            form.addEventListener('submit', function(e) {
                console.log('Form is being submitted');
                console.log('Form action:', this.action);
                console.log('Form method:', this.method);
                
                // Log form data
                const formData = new FormData(this);
                console.log('Form data:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>