<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Test Oluşturma Sayfası
        </h2>
    </x-slot>

    <div class="py-12 text-white">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl bg-dark dark:bg-gray-800 sm:rounded-lg">
                <!-- Test Oluşturma Formu -->
                <div class="p-3 text-white card bg-dark">
                        <!-- Hata Mesajlarını Göster -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('quiz.add') }}" id="formId" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">

                        <div class="m-2 text-white card bg-dark">
                            <div class="card-body">
                                <!-- Test Adı Girişi -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="test_name">Test Adı:</label>
                                            <input type="text" name="test_name" id="test_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="duration_minutes">Süre (Dakika):</label>
                                            <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="learning_purpose">Öğrenme Amacı:</label>
                                            <select name="learning_purpose" id="learning_purpose" class="form-control">
                                                <option value="egitim">Eğitim</option>
                                                <option value="is">İş</option>
                                                <option value="seyehat">Seyahat</option>
                                                <option value="eglence">Eğlence</option>
                                                <option value="kultur">Kültür</option>
                                                <option value="ailevearkadaslar">Aile ve Arkadaşlar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="language_level">Test Seviyesi:</label>
                                            <select name="language_level" id="language_level" class="form-control">
                                                <option value="a1">A1</option>
                                                <option value="a2">A2</option>
                                                <option value="b1">B1</option>
                                                <option value="b2">B2</option>
                                                <option value="c1">C1</option>
                                                <option value="c2">C2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Soruları ve Seçenekleri Ekleme -->
                        <div id="questions_section">
                            <!-- Buraya otomatik olarak soru alanları eklenecek -->
                        </div>
                        <!-- Yeni Soru Ekle Butonu -->
                        <button type="button" class="m-2 btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">Yeni Soru Ekle</button>

                        <!-- Gönderme Butonu -->
                        <button type="submit" class="m-2 btn btn-danger" onclick="return validateForm()">Testi
                            Oluştur</button>
                    </form>
                </div>
            </div>


        </div>
    </div>
    <!-- Modal -->
    <div class="text-white modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Soru Ekle</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item" onclick="addQuestion(1)">Seçenekli Soru</li>
                        {{-- <li class="list-group-item" onclick="addQuestion(2)">Eşleştirmeli Soru</li> --}}
                        <!-- Diğer soru tipleri buraya eklenebilir -->
                    </ul>
                </div>
                <div class="hidden modal-footer">
                    <button type="button" class="btn btn-secondary" id="closeModal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script src="{{ asset('js/quiz/create.js') }}"></script>
    @endsection
</x-app-layout>






