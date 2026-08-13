@echo off
setlocal

set "ROOT_DIR=%~dp0.."
for %%I in ("%ROOT_DIR%") do set "ROOT_DIR=%%~fI"
echo Menghentikan service lama jika masih berjalan...
call "%ROOT_DIR%\scripts\stop-system.bat"

call powershell -NoProfile -ExecutionPolicy Bypass -File "%ROOT_DIR%\scripts\start-background.ps1"

powershell -NoProfile -Command "$deadline=(Get-Date).AddSeconds(20); do { try { $client = New-Object System.Net.Sockets.TcpClient; if ($client.ConnectAsync('127.0.0.1',8000).Wait(1000)) { $client.Close(); exit 0 }; $client.Close() } catch {}; Start-Sleep -Milliseconds 500 } while ((Get-Date) -lt $deadline); exit 1"
if errorlevel 1 (
  echo PERINGATAN: Laravel belum siap di port 8000, browser mungkin belum bisa dibuka.
)

echo Membuka website...
start "" http://127.0.0.1:8000

echo Sistem sedang dijalankan.
echo FastAPI: http://127.0.0.1:5001
echo Laravel: http://127.0.0.1:8000

endlocal
