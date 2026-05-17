$rootDir = Resolve-Path (Join-Path $PSScriptRoot "..")
$laravelDir = Join-Path $rootDir "frontend-laravel"

Set-Location -LiteralPath $laravelDir

Write-Host "Laravel frontend running at http://127.0.0.1:8000" -ForegroundColor Cyan
powershell -ExecutionPolicy Bypass -File .\serve-laravel.ps1
