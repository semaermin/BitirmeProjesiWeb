<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-3 overflow-hidden text-white shadow-xl bg-dark dark:bg-gray-800 sm:rounded-lg">
                <h3 class="m-2 text-lg font-semibold">{{ auth()->user()->name }} Dashboardı</h3>

                <div class="py-2 row">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Oluşturulan Testler</h5>
                                    <span class="count">{{ $testCount }}</span>
                                </div>
                                <i class="fa-regular fa-file-lines fa-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Oluşturulan Sorular</h5>
                                    <span class="count">{{ $questionCount }}</span>
                                </div>
                                <i class="fa-solid fa-clipboard-question fa-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Oluşturulan Videolar</h5>
                                    <span class="count">{{ $videoCount }}</span>
                                </div>
                                <i class="fa-solid fa-file-video fa-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('quiz.quiz') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white transition duration-150 ease-in-out bg-gray-600 border border-transparent rounded-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ Auth::user()->name }} adminin oluşturduğu testlere git
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
