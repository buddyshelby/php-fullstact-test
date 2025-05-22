# Jawaban No. 16

### Requirements

PHP:
   - PHP 8.2.12

Laravel:
   - Laravel Framework 12.13.0

Download repository

1. Buka my-client


`composer install`

2. Rename .env.example ubah database config ke postgres lalu rename menjadi .env

3. Generate APP_KEY untuk aplikasi baru Laravel. ini akan setup env file juga nantinya.

`php artisan key:generate`


4. Migrasi databasenya untuk membuat table yang diperlukan.

`php artisan migrate`


5. jalankan backend secara lokal

`php artisan serve`

LIST API REST

- 