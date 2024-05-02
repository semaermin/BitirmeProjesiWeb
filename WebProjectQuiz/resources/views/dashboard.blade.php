<x-app-layout>


    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-3 overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <h3 class="text-lg font-semibold">{{ auth()->user()->name }} Dashboardı</h3>
                <a href="{{ route('quiz.quiz')}}">Adminin oluşturduğu testlere burdan ulaş</a>
                @if(Auth::check())
                <p>{{ Auth::user()->name }} kullanıcı oturum açtı.</p>
                @else
                    <p>Oturum açan kullanıcı bulunmamaktadır.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
