<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-3 overflow-hidden text-white shadow-xl bg-dark dark:bg-gray-800 sm:rounded-lg">
                <h3 class="m-2 text-lg font-semibold">{{ auth()->user()->name }} Dashboardı</h3>
                <a href="{{ route('quiz.quiz')}}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest transition duration-150 ease-in-out bg-white border-transparent rounded-md dark:border dark:text-gray-400 hover:bg-gray-700 hover:text-black dark:hover:focus:bg-gray-700 dark:focus: active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ Auth::user()->name }} adminin oluşturduğu testlere buradan ulaş</a>
                {{-- @if(Auth::check())
                <p>{{ Auth::user()->name }} kullanıcı oturum açtı.</p>
                @else
                    <p>Oturum açan kullanıcı bulunmamaktadır.</p>
                @endif --}}
            </div>
        </div>
    </div>
</x-app-layout>
