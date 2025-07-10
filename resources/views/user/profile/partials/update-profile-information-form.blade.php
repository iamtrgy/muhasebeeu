<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('user.profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <x-ui.form.group>
        <x-ui.form.input 
            id="name" 
            name="name" 
            type="text" 
            label="{{ __('Name') }}"
            :value="old('name', $user->name)" 
            required 
            autofocus 
            autocomplete="name"
            :error="$errors->get('name')"
        />
    </x-ui.form.group>

    <x-ui.form.group>
        <x-ui.form.input 
            id="email" 
            name="email" 
            type="email" 
            label="{{ __('Email') }}"
            :value="old('email', $user->email)" 
            required 
            autocomplete="username"
            :error="$errors->get('email')"
        />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    {{ __('Your email address is unverified.') }}
                </p>

                <div class="mt-2">
                    <x-ui.button.secondary form="send-verification" type="submit">
                        {{ __('Click here to re-send the verification email.') }}
                    </x-ui.button.secondary>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm text-green-600 dark:text-green-400">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif
    </x-ui.form.group>

    <div class="flex items-center gap-4">
        <x-ui.button.primary type="submit">{{ __('Save Profile') }}</x-ui.button.primary>

        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-green-600 dark:text-green-400"
            >{{ __('Profile updated successfully.') }}</p>
        @endif
    </div>
</form>
