<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-ui.modal.base name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" :closeButton="true">
        <form method="post" action="{{ route('user.profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-ui.form.input
                    id="password"
                    name="password"
                    type="password"
                    label="{{ __('Password') }}"
                    placeholder="{{ __('Password') }}"
                    class="w-3/4"
                    :error="$errors->userDeletion->first('password')"
                />
            </div>

            <div class="mt-6 flex justify-end">
                <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'confirm-user-deletion')">
                    {{ __('Cancel') }}
                </x-ui.button.secondary>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-ui.modal.base>
</section>
