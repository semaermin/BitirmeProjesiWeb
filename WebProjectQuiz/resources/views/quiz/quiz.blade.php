<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-3 overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <h3 class="m-2 text-lg font-semibold ">Testler</h3>
                <div class="list-group list-group-light">
                    @foreach($tests as $test)
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('quiz.show', $test->slug) }}" class="px-3 border-0 list-group-item list-group-item-action">{{ $test->name }}</a>
                            <a href="{{ route('quiz.edit', $test->slug) }}" class="mr-2 btn btn-outline-primary btn-sm">Düzenle</a>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('quiz.add') }}" class="m-3 btn btn-outline-primary btn-sm">Test Oluştur</a>
            </div>
        </div>
    </div>
</x-app-layout>
