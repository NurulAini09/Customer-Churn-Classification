@echo off
setlocal

set "ROOT_DIR=%~dp0.."
for %%I in ("%ROOT_DIR%") do set "ROOT_DIR=%%~fI"
set "PYTHON_DIR=%ROOT_DIR%\backend-python"
set "LARAVEL_DIR=%ROOT_DIR%\frontend-laravel"
set "PYTHON_EXE=%PYTHON_DIR%\.venv\Scripts\python.exe"
set "PYTHON_BOOTSTRAP=C:\laragon\bin\python\python-3.10\python.exe"

echo Menyiapkan Python virtual environment...
if not exist "%PYTHON_EXE%" (
  cd /d "%PYTHON_DIR%"
  if exist "%PYTHON_BOOTSTRAP%" (
    call "%PYTHON_BOOTSTRAP%" -m venv .venv
  ) else (
    where py >nul 2>nul
    if not errorlevel 1 (
      call py -3.10 -m venv .venv
    ) else (
      where python >nul 2>nul
      if not errorlevel 1 (
        call python -m venv .venv
      ) else (
        echo Python tidak ditemukan. Instal Python atau Laragon Python terlebih dahulu.
        exit /b 1
      )
    )
  )
)

if not exist "%PYTHON_EXE%" (
  echo Virtual environment gagal dibuat di "%PYTHON_DIR%".
  exit /b 1
)

cd /d "%PYTHON_DIR%"
call "%PYTHON_EXE%" -m pip install -r requirements.txt
if errorlevel 1 exit /b 1

echo Menyiapkan frontend assets...
cd /d "%LARAVEL_DIR%"
if exist package.json (
  call npm install --cache .\.npm-cache
  if errorlevel 1 exit /b 1
  call npm run build
  if errorlevel 1 exit /b 1
) else (
  echo package.json tidak ditemukan di "%LARAVEL_DIR%".
  exit /b 1
)

echo Setup selesai.
endlocal
