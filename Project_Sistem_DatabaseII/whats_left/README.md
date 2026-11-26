# What's Left

Aplikasi web Food Rescue (Laravel 13, MySQL, Blade, Alpine.js, Tailwind, Sanctum).

## Menjalankan
```bash
composer install
cp .env.example .env && php artisan key:generate   # JANGAN timpa .env yang sudah ada
php artisan migrate          # aman untuk DB yang tabelnya sudah ada
php artisan storage:link
php artisan schedule:work    # kedaluwarsa otomatis (Windows: run-scheduler.bat)
php artisan test             # memakai SQLite in-memory, tidak menyentuh MySQL
```
Dokumen: `CHECKLIST.md`, `CHANGELOG_V10.md`.
