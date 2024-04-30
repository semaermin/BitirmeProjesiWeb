<x-form-section submit="createTeam">
    <x-slot name="title">
        {{ __('Takım Detayları') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Projelerde başkalarıyla işbirliği yapmak için yeni bir ekip oluşturun.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-label value="{{ __('Takım Sahibi') }}" />

            <div class="flex items-center mt-2">
                <img class="object-cover w-12 h-12 rounded-full" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                <div class="leading-tight ms-4">
                    <div class="text-gray-900 dark:text-white">{{ $this->user->name }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $this->user->email }}</div>
                </div>
            </div>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Takıp Adı') }}" />
            <x-input id="name" type="text" class="block w-full mt-1" wire:model="state.name" autofocus />
            <x-input-error for="name" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-button>
            {{ __('Oluştur') }}
        </x-button>
    </x-slot>
</x-form-section>
