<form method="post" action="{{ route('user.profile.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <x-ui.form.group>
        <x-ui.form.input 
            id="update_password_current_password" 
            name="current_password" 
            type="password" 
            label="{{ __('Current Password') }}"
            autocomplete="current-password"
            :error="$errors->updatePassword->get('current_password')"
        />
    </x-ui.form.group>

    <x-ui.form.group>
        <x-ui.form.input 
            id="update_password_password" 
            name="password" 
            type="password" 
            label="{{ __('New Password') }}"
            autocomplete="new-password"
            :error="$errors->updatePassword->get('password')"
        />
    </x-ui.form.group>

    <x-ui.form.group>
        <x-ui.form.input 
            id="update_password_password_confirmation" 
            name="password_confirmation" 
            type="password" 
            label="{{ __('Confirm Password') }}"
            autocomplete="new-password"
            :error="$errors->updatePassword->get('password_confirmation')"
        />
    </x-ui.form.group>

    <div class="flex items-center gap-4">
        <x-ui.button.primary type="submit">{{ __('Save Password') }}</x-ui.button.primary>

        @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-green-600 dark:text-green-400"
            >{{ __('Password updated successfully.') }}</p>
        @endif
    </div>
</form>
