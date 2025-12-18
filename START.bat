@echo off
REM SIKA Application Startup Script
REM Start Apache and MySQL, then open browser

echo Starting SIKA Application...
echo.

REM Start XAMPP MySQL
echo Starting MySQL...
start "" "C:\xampp\mysql\bin\mysqld.exe"

REM Wait for MySQL to start
timeout /t 5 /nobreak

REM Start XAMPP Apache  
echo Starting Apache...
start "" "C:\xampp\apache\bin\httpd.exe"

REM Wait for Apache to start
timeout /t 3 /nobreak

REM Open browser
echo Opening application in browser...
start "" "http://localhost/sika/login.php"

echo.
echo SIKA Application started!
echo Username: admin
echo Password: password123
echo.
pause
