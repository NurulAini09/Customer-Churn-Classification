$rootDir = Resolve-Path (Join-Path $PSScriptRoot "..")
$pythonDir = Join-Path $rootDir "backend-python"
$pythonExe = Join-Path $pythonDir ".venv\Scripts\python.exe"

Set-Location -LiteralPath $pythonDir

if (-not (Test-Path -LiteralPath $pythonExe)) {
    Write-Host "Python virtual environment belum ada. Jalankan scripts\setup-system.bat terlebih dahulu." -ForegroundColor Yellow
    exit 1
}

Write-Host "FastAPI prediction service running at http://127.0.0.1:5001" -ForegroundColor Cyan
& $pythonExe -m uvicorn app.main:app --host 127.0.0.1 --port 5001 --env-file .env
