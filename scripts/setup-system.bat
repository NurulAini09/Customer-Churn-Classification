@echo off
setlocal

set "ROOT_DIR=%~dp0.."
for %%I in ("%ROOT_DIR%") do set "ROOT_DIR=%%~fI"
set "PYTHON_DIR=%ROOT_DIR%\backend-python"
set "LARAVEL_DIR=%ROOT_DIR%\frontend-laravel"
set "PYTHON_EXE=%PYTHON_DIR%\.venv\Scripts\python.exe"

echo Menyiapkan Python virtual environment...
if not exist "%PYTHON_EXE%" (
  cd /d "%PYTHON_DIR%"
  call python -m venv .venv
)

cd /d "%PYTHON_DIR%"
call "%PYTHON_EXE%" -m pip install -r requirements.txt

echo Menyiapkan frontend assets...
cd /d "%LARAVEL_DIR%"
if exist package.json (
  call npm install --cache .\.npm-cache
  call npm run build
) else (
  echo package.json tidak ditemukan di "%LARAVEL_DIR%".
  exit /b 1
)

echo Setup selesai.
endlocal
