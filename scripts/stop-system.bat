@echo off
setlocal

echo Menghentikan service pada port 5001 dan 8000...

for /f "tokens=5" %%a in ('netstat -ano ^| findstr ":5001" ^| findstr "LISTENING"') do (
  taskkill /PID %%a /F >nul 2>&1
)

for /f "tokens=5" %%a in ('netstat -ano ^| findstr ":8000" ^| findstr "LISTENING"') do (
  taskkill /PID %%a /F >nul 2>&1
)

echo Selesai.
endlocal
