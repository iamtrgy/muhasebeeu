<x-admin.layout title="Edit User">
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="userEditForm">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <x-ui.form.group>
                            <x-ui.form.input 
                                id="name" 
                                name="name" 
                                type="text" 
                                label="Name" 
                                :value="old('name', $user->name)" 
                                :error="$errors->get('name')" 
                                required 
                                autofocus 
                            />
                        </x-ui.form.group>

                        <!-- Email Address -->
                        <x-ui.form.group>
                            <x-ui.form.input 
                                id="email" 
                                name="email" 
                                type="email" 
                                label="Email" 
                                :value="old('email', $user->email)" 
                                :error="$errors->get('email')" 
                                required 
                            />
                        </x-ui.form.group>

                        <!-- User Roles -->
                        <x-ui.form.group>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">User Roles</label>
                            <div class="space-y-3">
                                <x-ui.form.checkbox 
                                    id="is_admin" 
                                    name="is_admin" 
                                    :checked="$user->is_admin" 
                                    label="Administrator" 
                                    description="Full system access" 
                                />
                                
                                <x-ui.form.checkbox 
                                    id="is_accountant" 
                                    name="is_accountant" 
                                    :checked="$user->is_accountant" 
                                    label="Accountant" 
                                    description="Can access assigned users and companies" 
                                />
                            </div>
                        </x-ui.form.group>

                        <!-- Password -->
                        <x-ui.form.group>
                            <x-ui.form.input 
                                id="password" 
                                name="password" 
                                type="password" 
                                label="Password" 
                                :error="$errors->get('password')" 
                                help="Leave blank to keep the current password" 
                            />
                        </x-ui.form.group>

                        <!-- Confirm Password -->
                        <x-ui.form.group>
                            <x-ui.form.input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                label="Confirm Password" 
                                :error="$errors->get('password_confirmation')" 
                            />
                        </x-ui.form.group>

                        <div class="flex items-center justify-end gap-3 mt-6">
                            <x-ui.button.secondary :href="route('admin.users.show', $user)">
                                {{ __('Cancel') }}
                            </x-ui.button.secondary>
                            
                            <x-ui.button.primary type="submit">
                                {{ __('Update User') }}
                            </x-ui.button.primary>
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
</x-admin.layout>