<div class="space-y-4">
    <p class="text-sm text-red-600 dark:text-red-400">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <x-ui.button.danger
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('Delete Account') }}
    </x-ui.button.danger>
</div>

<x-ui.modal.base name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()">
    <form method="post" action="{{ route('user.profile.destroy') }}" class="space-y-6">
        @csrf
        @method('delete')

        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h3>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>
        </div>

        <x-ui.form.group>
            <x-ui.form.input
                id="password"
                name="password"
                type="password"
                label="{{ __('Password') }}"
                placeholder="{{ __('Enter your password to confirm') }}"
                :error="$errors->userDeletion->get('password')"
                required
            />
        </x-ui.form.group>

        <div class="flex justify-end gap-3">
            <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'confirm-user-deletion')">
                {{ __('Cancel') }}
            </x-ui.button.secondary>

            <x-ui.button.danger type="submit">
                {{ __('Delete Account') }}
            </x-ui.button.danger>
        </div>
    </form>
</x-ui.modal.base>
