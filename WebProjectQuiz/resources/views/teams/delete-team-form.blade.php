<x-action-section>
    <x-slot name="title">
        {{ __('Takım Sil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Bu ekibi kalıcı olarak silin.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Bir ekip silindiğinde, ekibin tüm kaynakları ve verileri kalıcı olarak silinir. Bu ekibi silmeden önce lütfen bu ekiple ilgili tutmak istediğiniz tüm verileri veya bilgileri indirin.') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                {{ __('Takım Sil') }}
            </x-danger-button>
        </div>

        <!-- Delete Team Confirmation Modal -->
        <x-confirmation-modal wire:model.live="confirmingTeamDeletion">
            <x-slot name="title">
                {{ __('Takım Sil') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Bu ekibi silmek istediğinizden emin misiniz? Bir ekip silindiğinde, ekibin tüm kaynakları ve verileri kalıcı olarak silinir.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                    {{ __('Sil') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteTeam" wire:loading.attr="disabled">
                    {{ __('Takım Sil') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </x-slot>
</x-action-section>
