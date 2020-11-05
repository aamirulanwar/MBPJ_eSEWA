echo off
cls
echo **********************************************************************
echo Penjanaan bil awal tahun bagi semua akaun yang masih aktif
echo **********************************************************************
echo.
cd C:\laragon\www\MPKJ_GITHUB
php index.php cron generate_bill_awal_tahun
timeout 10
exit