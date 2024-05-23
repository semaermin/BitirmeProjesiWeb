<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Test Adı: {{ $test->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden text-white shadow-xl bg-dark dark:bg-gray-800 sm:rounded-lg">
                <h2 class="m-3">
                    Test Seviyesi: {{ $test->language_level }} - Öğrenme Amacı: {{ $test->learning_purpose }}
                </h2>
                <div class="m-2 list-group">
                    @foreach($test->questions as $index => $question)
                        <div class="list-group-item list-group-item-light">
                            <h5 class="mb-1">{{ $index + 1 }}.) {{ $question->text }}</h5>
                            <br>
                            {{-- @if ($question->media_path)
                                <img src="{{ asset('storage/' . $question->media_path) }}" alt="Soru Resmi" width="200">
                            @else
                                <p>Soru için fotoğraf yok</p>
                            @endif --}}
                            @if ($question->media_path)
                                @if (pathinfo($question->media_path, PATHINFO_EXTENSION) == 'mp4')
                                    <video width="320" height="240" controls>
                                        <source src="{{ asset('storage/' . $question->media_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $question->media_path) }}" alt="Soru Resmi" width="250">
                                @endif
                            @else
                                <p>Soru için medya dosyası yok</p>
                            @endif


                            <ul class="list-group list-group-flush">
                                @if($question->type == 1)
                                    {{-- Çoktan Seçmeli Soru --}}
                                    @foreach($question->answers as $answer)
                                        <li class="list-group-item list-group-item-light">
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
                                        <li class="list-group-item list-group-item-light">
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
                <a href="{{ route('quiz.edit', $test->slug ) }}" class="m-3 btn btn-danger btn-sm">Düzenle</a>
            </div>
        </div>
    </div>
</x-app-layout>
