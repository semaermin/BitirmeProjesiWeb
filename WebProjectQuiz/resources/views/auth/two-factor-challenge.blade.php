<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-show="! recovery">
                {{ __('Lütfen kimlik doğrulayıcı uygulamanız tarafından sağlanan kimlik doğrulama kodunu girerek hesabınıza erişimi onaylayın.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-cloak x-show="recovery">
                {{ __('Lütfen acil durum kurtarma kodlarınızdan birini girerek hesabınıza erişimi onaylayın.') }}
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-label for="code" value="{{ __('Kod') }}" />
                    <x-input id="code" class="block w-full mt-1" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4" x-cloak x-show="recovery">
                    <x-label for="recovery_code" value="{{ __('Kurtarma kodu') }}" />
                    <x-input id="recovery_code" class="block w-full mt-1" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 underline cursor-pointer dark:text-gray-400 hover:text-gray-900"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        {{ __('Kurtarma kodu kullanın') }}
                    </button>

                    <button type="button" class="text-sm text-gray-600 underline cursor-pointer dark:text-gray-400 hover:text-gray-900"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        {{ __('Kimlik doğrulama kodu kullanın') }}
                    </button>

                    <x-button class="ms-4">
                        {{ __('Giriş') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
