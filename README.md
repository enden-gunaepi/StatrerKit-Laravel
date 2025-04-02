# Laravel 12 Role & Permission Kit

🚀 **Laravel 12 Role & Permission Kit** adalah Starter Kit berbasis **Laravel 12** yang siap pakai, dilengkapi dengan fitur manajemen pengguna dan kontrol akses berbasis peran.

## ✨ Fitur Utama  
- ✅ **Role & Permission Management** Memungkinkan pembuatan role dan permission secara dinamis.
- ✅ **User Management** Menyediakan fitur CRUD lengkap untuk pengguna.
- ✅ **Log Activity** Mencatat aktivitas pengguna, baik secara umum atau berdasarkan pengguna yang sedang login.
- ✅ **Dibangun dengan standar Laravel**, siap untuk dikembangkan lebih lanjut  

⚡ Cocok untuk proyek yang membutuhkan sistem autentikasi dengan **kontrol akses berbasis peran**!  

## 📦 Instalasi  

```bash
git clone https://github.com/ginginabdulgoni/larakit12.git
cd larakit12
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed


```
## 🌟 Konfigurasi Database
Tambahkan atau sesuaikan pengaturan database pada file .env sesuai dengan server, username, dan password database Anda:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=larakit12
DB_USERNAME=root
DB_PASSWORD=root
```
## 🌟 SOLUSI: Buat Folder Cache & Perbaiki Permission

Coba jalankan perintah berikut untuk memastikan semua folder yang dibutuhkan ada dan memiliki izin yang benar:

```bash
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
chmod -R 777 storage bootstrap/cache

```

Setelah itu, jalankan kembali aplikasi dengan:
```bash
php artisan serve
```
### Dukungan

Jika Anda merasa proyek ini bermanfaat dan ingin mendukung saya, traktir kopi saya melalui:


* [Trakteer Kopi](https://trakteer.id/ginginabdulgoni/tip)
* [Paypal](https://paypal.me/ginginabdulgoni)
# larakit12
