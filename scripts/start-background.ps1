$ErrorActionPreference = "Stop"

$rootDir = Resolve-Path (Join-Path $PSScriptRoot "..")
$pythonDir = Join-Path $rootDir "backend-python"
$laravelDir = Join-Path $rootDir "frontend-laravel"
$pythonExe = Join-Path $pythonDir ".venv\Scripts\python.exe"

if (-not (Test-Path -LiteralPath $pythonExe)) {
    Write-Host "Python virtual environment belum ada. Membuat .venv dan menginstall dependency..."
    Set-Location -LiteralPath $pythonDir
    python -m venv .venv
    & $pythonExe -m pip install -r requirements.txt
}

$phpCommand = Get-Command php -ErrorAction Stop
$phpPath = $phpCommand.Source
$extensionDir = Join-Path (Split-Path $phpPath -Parent) "ext"

Write-Host "Menjalankan FastAPI prediction service..."
$fastApiProcess = Start-Process `
    -FilePath $pythonExe `
    -ArgumentList @("-m", "uvicorn", "app.main:app", "--host", "127.0.0.1", "--port", "5001", "--env-file", ".env") `
    -WorkingDirectory $pythonDir `
    -WindowStyle Hidden `
    -PassThru

Start-Sleep -Seconds 2

Write-Host "Menjalankan Laravel frontend..."
$laravelProcess = Start-Process `
    -FilePath $phpPath `
    -ArgumentList @("-d", "extension_dir=$extensionDir", "-d", "extension=sqlite3", "-d", "extension=pdo_sqlite", "-S", "127.0.0.1:8000", "-t", "public", "router.php") `
    -WorkingDirectory $laravelDir `
    -WindowStyle Hidden `
    -PassThru

Start-Sleep -Seconds 2

function Test-Port {
    param([int] $Port)

    try {
        $client = [System.Net.Sockets.TcpClient]::new()
        $connected = $client.ConnectAsync("127.0.0.1", $Port).Wait(1000)
        $client.Close()
        return $connected
    } catch {
        return $false
    }
}

$fastApiReady = Test-Port -Port 5001
$laravelReady = Test-Port -Port 8000

Write-Host "FastAPI PID: $($fastApiProcess.Id) | Ready: $fastApiReady"
Write-Host "Laravel PID: $($laravelProcess.Id) | Ready: $laravelReady"

if (-not $fastApiReady) {
    Write-Host "PERINGATAN: FastAPI belum listen di port 5001." -ForegroundColor Yellow
}

if (-not $laravelReady) {
    Write-Host "PERINGATAN: Laravel belum listen di port 8000." -ForegroundColor Yellow
}
