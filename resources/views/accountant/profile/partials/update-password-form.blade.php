<section>
    <form method="post" action="{{ route('accountant.profile.password.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-ui.form.input
                id="update_password_current_password"
                name="current_password"
                type="password"
                label="{{ __('Current Password') }}"
                autocomplete="current-password"
                :error="$errors->updatePassword->first('current_password')"
            />
        </div>

        <div>
            <x-ui.form.input
                id="update_password_password"
                name="password"
                type="password"
                label="{{ __('New Password') }}"
                autocomplete="new-password"
                :error="$errors->updatePassword->first('password')"
            />
        </div>

        <div>
            <x-ui.form.input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                label="{{ __('Confirm Password') }}"
                autocomplete="new-password"
                :error="$errors->updatePassword->first('password_confirmation')"
            />
        </div>

        <div class="flex items-center gap-4">
            <x-ui.button.primary type="submit">{{ __('Save') }}</x-ui.button.primary>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>