echo off
cls
echo *****************************************************************************
echo Penjanaan bil bulanan secara automatik bagi sistem sewaan sedang berjalan
echo *****************************************************************************
echo.
cd C:\laragon\www\MPKJ_GITHUB
php index.php cron generate_bill
timeout 10
exit