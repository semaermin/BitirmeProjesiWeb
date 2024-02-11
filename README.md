# İngilizce Eğitim/Sınav Uygulama Sitesi


## Kurulum
1. Bu repoyu klonla
```
git clone https://github.com/semaermin/BitirmeProjesiWeb
```

2. WebProjectQuiz dosyasına cd ile gidin ve Composer paketlerini yükleyin
```
cd WebProjectQuiz
```
```
composer install
```

3. Create and setup .env file
```
cp .env.example .env
```
```
php artisan key:generate
```
```
php artisan jwt:secret
```

4. DB_TEST_DATABASE üzerinde veritabanı adı eklemeyi test etmek için veritabanı kimlik bilgilerini .env dosyasına koyun


6. Kayıtları taşıyın ve ekleyin
```
php artisan migrate --seed
```
