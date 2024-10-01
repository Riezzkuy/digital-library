## System requirement
1. PHP 8.1+
1. Composer 2+
1. MariaDB 10.4/MySQL 5.7

## Quick setup
### 1. Install projek
via SSH
```sh
git clone git@github.com:presidenwashil/digital-library.git "digital-library"
```

via HTTPS
```sh
git clone https://github.com/presidenwashil/digital-library.git "digital-library"
```

Kemudian masuk ke directory dan install dependency.
```sh
cd "digital-library"
composer install
npm install
```

### 2. Setup environment
Copy file .env.example ke .env

Linux/Mac OS
```sh
cp .env.example .env
```

Windows (CMD)
```bat
copy .env.example .env
```

Windows (Powershell)
```powershell
Copy-Item .env.example -Destination .env
```

Kemudian konfigurasi isi .env sesuai dengan kebutuhan

### 3. Migrasi database
Jalankan perintah berikut untuk melakukan migrasi database, pastikan nama database sudah benar dan sesuai.
```sh
php artisan migrate
```

### 4. Menjalankan Projek
Jalankan perintah berikut ini untuk menjalankan server laravel.
```sh
php artisan serve
```

Jalankan perintah berikut ini agar style dapat diterapkan dengan baik menggunakan vite.
```sh
npm run dev
```

Pada project ini terdapat fungsi yang berjalan di belakang layar (background-job) gunakan perintah berikut.
```sh
php artisan queue:work
```
