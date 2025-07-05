<x-admin.layout 
    title="Create User"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Users'), 'href' => route('admin.users.index')],
        ['title' => __('Create')]
    ]"
>
    <div class="space-y-6" x-data="{ submitting: false }">
        <!-- Validation Error Summary -->
        @if ($errors->any())
            <x-ui.alert variant="danger">
                <x-slot name="title">There were errors with your submission</x-slot>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <!-- Form Card -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Add New User</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    Create a new user account. They will receive an email with login instructions.
                </p>
            </x-ui.card.header>
            
            <form method="POST" action="{{ route('admin.users.store') }}" @submit="submitting = true">
                @csrf
                
                <x-ui.card.body>
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <x-ui.form.input
                                label="Full Name"
                                name="name"
                                type="text"
                                :value="old('name')"
                                required
                                autofocus
                                placeholder="John Doe"
                                helperText="Enter the user's full name as it will appear throughout the system"
                                x-bind:disabled="submitting"
                            >
                                <x-slot name="leadingIcon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </x-slot>
                            </x-ui.form.input>
                        </div>

                        <!-- Email -->
                        <div>
                            <x-ui.form.input
                                label="Email Address"
                                name="email"
                                type="email"
                                :value="old('email')"
                                required
                                placeholder="john@example.com"
                                helperText="Must be a valid email address. The user will receive login instructions here."
                                autocomplete="email"
                                x-bind:disabled="submitting"
                            >
                                <x-slot name="leadingIcon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </x-slot>
                            </x-ui.form.input>
                        </div>

                        <!-- Password -->
                        <div>
                            <x-ui.form.input
                                label="Initial Password"
                                name="password"
                                type="password"
                                required
                                placeholder="Enter a secure password"
                                helperText="Must be at least 8 characters long. The user can change this after their first login."
                                autocomplete="new-password"
                                x-bind:disabled="submitting"
                            >
                                <x-slot name="leadingIcon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </x-slot>
                            </x-ui.form.input>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-ui.form.input
                                label="Confirm Password"
                                name="password_confirmation"
                                type="password"
                                required
                                placeholder="Re-enter the password"
                                helperText="Enter the same password again to confirm"
                                autocomplete="new-password"
                                x-bind:disabled="submitting"
                            >
                                <x-slot name="leadingIcon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </x-slot>
                            </x-ui.form.input>
                        </div>

                        <!-- Role Selection -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">User Roles</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Select the roles for this user. Users can have multiple roles.
                                </p>
                            </div>
                            
                            <div class="space-y-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                                <!-- Admin Checkbox -->
                                <x-ui.form.checkbox
                                    name="is_admin"
                                    id="is_admin"
                                    value="1"
                                    :checked="old('is_admin')"
                                    x-bind:disabled="submitting"
                                >
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Administrator</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block mt-1">
                                        Full system access including user management, company management, and all settings
                                    </span>
                                </x-ui.form.checkbox>

                                <!-- Accountant Checkbox -->
                                <x-ui.form.checkbox
                                    name="is_accountant"
                                    id="is_accountant"
                                    value="1"
                                    :checked="old('is_accountant')"
                                    x-bind:disabled="submitting"
                                >
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Accountant</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block mt-1">
                                        Can manage assigned users and companies, review and approve documents
                                    </span>
                                </x-ui.form.checkbox>
                                
                                <!-- Regular User Note -->
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        If no roles are selected, the user will be created as a regular user with basic access.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <x-ui.button.secondary 
                                href="{{ route('admin.users.index') }}"
                                x-bind:disabled="submitting"
                            >
                                Cancel
                            </x-ui.button.secondary>
                            <x-ui.button.primary 
                                type="submit"
                                x-bind:disabled="submitting"
                                x-bind:class="submitting ? 'opacity-75 cursor-wait' : ''"
                            >
                                <span x-show="!submitting" class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Create User
                                </span>
                                <span x-show="submitting" class="flex items-center">
                                    <x-ui.spinner size="sm" class="mr-2" />
                                    Creating...
                                </span>
                            </x-ui.button.primary>
                        </div>
                    </div>
                </x-ui.card.body>
            </form>
        </x-ui.card.base>
        
        <!-- Success Message (if redirected back with success) -->
        @if(session('success'))
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform translate-y-2 opacity-0"
                x-transition:enter-end="transform translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="transform translate-y-0 opacity-100"
                x-transition:leave-end="transform translate-y-2 opacity-0"
                class="fixed bottom-4 right-4 z-50"
            >
                <x-ui.alert variant="success">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </x-slot>
                    {{ session('success') }}
                </x-ui.alert>
            </div>
        @endif
    </div>
</x-admin.layout>