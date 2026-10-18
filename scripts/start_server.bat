@echo off
title Welkar Local Development Server
cd /d "%~dp0\.."

tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo MariaDB Server is already running.
) else (
    echo Starting MariaDB Server...
    start "" /B "C:\Program Files\MariaDB 12.3\bin\mysqld.exe" --defaults-file="C:\Program Files\MariaDB 12.3\data\my.ini" --console
    timeout /t 2 /nobreak >nul
)

echo Starting PHP Server on http://localhost:8000...
php -S 0.0.0.0:8000 -t public
pause
