<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Parolanızı mı unuttunuz? Sorun değil. Bize e-posta adresinizi bildirmeniz yeterli; size yeni bir şifre seçmenizi sağlayacak bir şifre sıfırlama bağlantısı e-postayla göndereceğiz.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Email Şifre Sıfırlama Bağlantısı') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
