<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <x-ui.form.input
            id="email"
            name="email"
            type="email"
            label="{{ __('Email') }}"
            :value="old('email')"
            required
            autofocus
            autocomplete="username"
            :error="$errors->first('email')"
        />

        <!-- Password -->
        <div class="mt-4">
            <x-ui.form.input
                id="password"
                name="password"
                type="password"
                label="{{ __('Password') }}"
                required
                autocomplete="current-password"
                :error="$errors->first('password')"
            />
        </div>

        <!-- Remember Me -->
        <div class="mt-4">
            <x-ui.form.checkbox
                id="remember_me"
                name="remember"
                label="{{ __('Remember me') }}"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
