<x-app-layout>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-3 overflow-hidden shadow-xl bg-dark dark:bg-gray-800 sm:rounded-lg">
                <h3 class="m-2 text-lg font-semibold text-white">Testler</h3>
                <div class="list-group list-group-dark">
                    @foreach($tests as $test)
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('quiz.show', $test->slug) }}" class="px-3 mr-2 border-0 text-white-200 list-group-item list-group-item-secondary list-group-item-action">{{ $test->name }}</a>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('quiz.add') }}" class="m-3 btn btn-danger btn-sm">Test Oluştur</a>
            </div>
        </div>
    </div>
</x-app-layout>
