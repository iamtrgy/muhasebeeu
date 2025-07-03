<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <x-ui.form.input
            id="email"
            name="email"
            type="email"
            label="{{ __('Email') }}"
            :value="old('email', $request->email)"
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
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
