<x-action-section>
    <x-slot name="title">
        {{ __('İki Faktörlü Kimlik Doğrulama') }}
    </x-slot>

    <x-slot name="description">
        {{ __('İki faktörlü kimlik doğrulamayı kullanarak hesabınıza ek güvenlik ekleyin.') }}
    </x-slot>

    <x-slot name="content">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    {{ __('İki faktörlü kimlik doğrulamayı etkinleştirmeyi tamamlayın.') }}
                @else
                    {{ __('İki faktörlü kimlik doğrulamayı etkinleştirdiniz.') }}
                @endif
            @else
                {{ __('İki faktörlü kimlik doğrulamayı etkinleştirmediniz.') }}
            @endif
        </h3>

        <div class="max-w-xl mt-3 text-sm text-gray-600 dark:text-gray-400">
            <p>
                {{ __('İki faktörlü kimlik doğrulama etkinleştirildiğinde, kimlik doğrulama sırasında sizden güvenli, rastgele bir belirteç istenecektir. Bu jetonu telefonunuzun Google Authenticator uygulamasından alabilirsiniz.') }}
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="max-w-xl mt-4 text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-semibold">
                        @if ($showingConfirmation)
                            {{ __('İki faktörlü kimlik doğrulamayı etkinleştirmeyi tamamlamak için telefonunuzun kimlik doğrulama uygulamasını kullanarak aşağıdaki QR kodunu tarayın veya kurulum anahtarını girin ve oluşturulan OTP kodunu girin.') }}
                        @else
                            {{ __('İki faktörlü kimlik doğrulama artık etkindir. Telefonunuzun kimlik doğrulama uygulamasını kullanarak aşağıdaki QR kodunu tarayın veya kurulum anahtarını girin.') }}
                        @endif
                    </p>
                </div>

                <div class="inline-block p-2 mt-4 bg-white">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="max-w-xl mt-4 text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-semibold">
                        {{ __('Kurulum Anahtarı') }}: {{ decrypt($this->user->two_factor_secret) }}
                    </p>
                </div>

                @if ($showingConfirmation)
                    <div class="mt-4">
                        <x-label for="code" value="{{ __('Kod') }}" />

                        <x-input id="code" type="text" name="code" class="block w-1/2 mt-1" inputmode="numeric" autofocus autocomplete="one-time-code"
                            wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication" />

                        <x-input-error for="code" class="mt-2" />
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="max-w-xl mt-4 text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-semibold">
                        {{ __('Bu kurtarma kodlarını güvenli bir şifre yöneticisinde saklayın. İki faktörlü kimlik doğrulama cihazınızın kaybolması durumunda hesabınıza erişimi kurtarmak için kullanılabilirler.') }}
                    </p>
                </div>

                <div class="grid max-w-xl gap-1 px-4 py-4 mt-4 font-mono text-sm bg-gray-100 rounded-lg dark:bg-gray-900 dark:text-gray-100">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-5">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button type="button" wire:loading.attr="disabled">
                        {{ __('Etkinleştir') }}
                    </x-button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-secondary-button class="me-3">
                            {{ __('Kurtarma Kodlarını Yeniden Oluşturun') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <x-button type="button" class="me-3" wire:loading.attr="disabled">
                            {{ __('Onayla') }}
                        </x-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <x-secondary-button class="me-3">
                            {{ __('Kurtarma Kodlarını Göster') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-secondary-button wire:loading.attr="disabled">
                            {{ __('Sil') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-danger-button wire:loading.attr="disabled">
                            {{ __('Devre Dışı Bırak') }}
                        </x-danger-button>
                    </x-confirms-password>
                @endif

            @endif
        </div>
    </x-slot>
</x-action-section>
