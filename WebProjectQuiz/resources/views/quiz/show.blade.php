<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ $test->name }} - Quiz
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <div class="list-group list-group-flush">
                    @foreach($test->questions as $index => $question)
                        <div class="list-group-item">
                            <h5 class="mb-1">{{ $index + 1 }}.) {{ $question->text }}</h5>
                            <br>
                            @if ($question->media_path)
                                <img src="{{ asset('storage/' . $question->media_path) }}" alt="Soru Resmi" width="200">
                            @else
                                <p>Soru için fotoğraf yok</p>
                            @endif

                            <ul class="list-group list-group-flush">
                                @if($question->type == 1)
                                    {{-- Çoktan Seçmeli Soru --}}
                                    @foreach($question->answers as $answer)
                                        <li class="list-group-item">
                                            {{ $answer->text }} -
                                            @if($answer->is_correct)
                                                <span class="text-success">Doğru Cevap</span>
                                            @else
                                                <span class="text-danger">Yanlış Cevap</span>
                                            @endif
                                        </li>
                                    @endforeach
                                @elseif($question->type == 2)
                                    {{-- Eşleştirme Sorusu --}}
                                    @foreach($question->matchingOptions->groupBy('pair_order') ?? [] as $pairOrder => $matchingOptions)
                                        <li class="list-group-item">
                                            @foreach($matchingOptions as $index => $matchingOption)
                                                {{ $matchingOption->option_text }}
                                                @if(!$loop->last)
                                                    - <!-- Son seçenek değilse, araya boşluk ekleyelim -->
                                                @endif
                                            @endforeach
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
