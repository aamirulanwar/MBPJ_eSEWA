echo off
cls
echo **********************************************************************
echo Pemprosesan bayaran bil setiap hari sedang dijalankan
echo **********************************************************************
echo.
cd C:\laragon\www\MPKJ_GITHUB
php index.php cron update_payment_transaction
timeout 10
exit