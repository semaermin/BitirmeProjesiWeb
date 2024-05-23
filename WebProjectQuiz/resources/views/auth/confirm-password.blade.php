<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Bu, uygulamanın güvenli bir alanıdır. Devam etmeden önce lütfen şifrenizi doğrulayın.') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="{{ __('Parola') }}" />
                <x-login-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Onayla') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
