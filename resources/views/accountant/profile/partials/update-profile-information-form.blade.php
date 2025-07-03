<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('accountant.profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-ui.form.input
                id="name"
                name="name"
                type="text"
                label="{{ __('Name') }}"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
                :error="$errors->first('name')"
            />
        </div>

        <div>
            <x-ui.form.input
                id="email"
                name="email"
                type="email"
                label="{{ __('Email') }}"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
                :error="$errors->first('email')"
            />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <x-ui.button.primary type="submit" form="send-verification" size="sm" class="ml-2">
                            {{ __('Click here to re-send the verification email.') }}
                        </x-ui.button.primary>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-ui.button.primary>{{ __('Save') }}</x-ui.button.primary>

            @if (session('status') === 'profile-updated')
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