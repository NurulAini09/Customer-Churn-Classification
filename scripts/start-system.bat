@echo off
setlocal

set "ROOT_DIR=%~dp0.."
for %%I in ("%ROOT_DIR%") do set "ROOT_DIR=%%~fI"
echo Menghentikan service lama jika masih berjalan...
call "%ROOT_DIR%\scripts\stop-system.bat"

call powershell -NoProfile -ExecutionPolicy Bypass -File "%ROOT_DIR%\scripts\start-background.ps1"

echo Membuka website...
start "" http://127.0.0.1:8000

echo Sistem sedang dijalankan.
echo FastAPI: http://127.0.0.1:5001
echo Laravel: http://127.0.0.1:8000

endlocal
