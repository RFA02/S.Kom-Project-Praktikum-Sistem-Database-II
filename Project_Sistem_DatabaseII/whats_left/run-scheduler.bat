@echo off
cd /d "%~dp0"
echo Menjalankan scheduler What's Left (tekan Ctrl+C untuk berhenti)...
php artisan schedule:work
