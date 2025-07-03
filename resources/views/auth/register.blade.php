<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <x-ui.form.input
            id="name"
            name="name"
            type="text"
            label="{{ __('Name') }}"
            :value="old('name')"
            required
            autofocus
            autocomplete="name"
            :error="$errors->first('name')"
        />

        <!-- Email Address -->
        <div class="mt-4">
            <x-ui.form.input
                id="email"
                name="email"
                type="email"
                label="{{ __('Email') }}"
                :value="old('email')"
                required
                autocomplete="username"
                :error="$errors->first('email')"
            />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-ui.form.input
                id="password"
                name="password"
                type="password"
                label="{{ __('Password') }}"
                required
                autocomplete="new-password"
                :error="$errors->first('password')"
            />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-ui.form.input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                label="{{ __('Confirm Password') }}"
                required
                autocomplete="new-password"
                :error="$errors->first('password_confirmation')"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
