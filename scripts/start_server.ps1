# Start MariaDB if not already running
$mariaProcess = Get-Process -Name "mysqld" -ErrorAction SilentlyContinue
if (-not $mariaProcess) {
    Write-Host "Starting MariaDB Server..." -ForegroundColor Cyan
    Start-Process -FilePath "C:\Program Files\MariaDB 12.3\bin\mysqld.exe" -ArgumentList '--defaults-file="C:\Program Files\MariaDB 12.3\data\my.ini"', '--console' -WindowStyle Hidden
    Start-Sleep -Seconds 2
} else {
    Write-Host "MariaDB Server is already running." -ForegroundColor Green
}

# Start PHP Development Server
Write-Host "Starting PHP Server on http://localhost:8000..." -ForegroundColor Cyan
php -S 0.0.0.0:8000 -t public
