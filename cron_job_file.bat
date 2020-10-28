echo off
cd C:\laragon\www\MPKJ_GITHUB
php index.php cron generate_bill
timeout 10
exit