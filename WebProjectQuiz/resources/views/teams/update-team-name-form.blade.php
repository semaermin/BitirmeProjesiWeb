<x-form-section submit="updateTeamName">
    <x-slot name="title">
        {{ __('Takım Adı') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Takımın adı ve sahibi bilgileri.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Takım Sahibi Bilgisi -->
        <div class="col-span-6">
            <x-label value="{{ __('Takım Sahibi') }}" />

            <div class="flex items-center mt-2">
                <img class="object-cover w-12 h-12 rounded-full" src="{{ $team->owner->profile_photo_url }}" alt="{{ $team->owner->name }}">

                <div class="leading-tight ms-4">
                    <div class="text-gray-900 dark:text-white">{{ $team->owner->name }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $team->owner->email }}</div>
                </div>
            </div>
        </div>

        <!-- Takım Adı -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Takım Adı') }}" />

            <x-input id="name"
                        type="text"
                        class="block w-full mt-1"
                        wire:model="state.name"
                        :disabled="! Gate::check('update', $team)" />

            <x-input-error for="name" class="mt-2" />
        </div>
    </x-slot>

    @if (Gate::check('update', $team))
        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Kaydedildi.') }}
            </x-action-message>

            <x-button>
                {{ __('Kaydet') }}
            </x-button>
        </x-slot>
    @endif
</x-form-section>
