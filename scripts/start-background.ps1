$ErrorActionPreference = "Stop"

$rootDir = Resolve-Path (Join-Path $PSScriptRoot "..")
$pythonDir = Join-Path $rootDir "backend-python"
$laravelDir = Join-Path $rootDir "frontend-laravel"
$pythonExe = Join-Path $pythonDir ".venv\Scripts\python.exe"

function New-PythonVirtualEnvironment {
    $laragonPython = "C:\laragon\bin\python\python-3.10\python.exe"

    if (Test-Path -LiteralPath $laragonPython) {
        & $laragonPython -m venv .venv
        return
    }

    if (Get-Command py -ErrorAction SilentlyContinue) {
        & py -3.10 -m venv .venv
        return
    }

    if (Get-Command python -ErrorAction SilentlyContinue) {
        & python -m venv .venv
        return
    }

    throw "Python tidak ditemukan. Instal Python atau perbarui script startup."
}

function Get-PhpExecutable {
    $candidates = @(
        "C:\laragon\bin\php\php-8.3.15-nts-Win32-vs16-x64\php.exe"
    )

    foreach ($candidate in $candidates) {
        if (Test-Path -LiteralPath $candidate) {
            return $candidate
        }
    }

    if (Get-Command php -ErrorAction SilentlyContinue) {
        return (Get-Command php -ErrorAction Stop).Source
    }

    throw "PHP tidak ditemukan. Pastikan Laragon tersedia atau tambahkan PHP ke PATH."
}

if (-not (Test-Path -LiteralPath $pythonExe)) {
    Write-Host "Python virtual environment belum ada. Membuat .venv dan menginstall dependency..."
    Set-Location -LiteralPath $pythonDir
    New-PythonVirtualEnvironment

    if (-not (Test-Path -LiteralPath $pythonExe)) {
        throw ".venv gagal dibuat di $pythonDir"
    }

    & $pythonExe -m pip install -r requirements.txt
}

$phpPath = Get-PhpExecutable
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
