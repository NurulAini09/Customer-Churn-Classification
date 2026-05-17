$phpCommand = Get-Command php -ErrorAction Stop
$phpPath = $phpCommand.Source
$extensionDir = Join-Path (Split-Path $phpPath -Parent) "ext"

if (-not (Test-Path $phpPath)) {
    throw "PHP executable tidak ditemukan. Pastikan PHP Laragon sudah masuk PATH."
}

if (-not (Test-Path $extensionDir)) {
    throw "Folder extension PHP tidak ditemukan: $extensionDir"
}

& $phpPath `
    -d "extension_dir=$extensionDir" `
    -d "extension=sqlite3" `
    -d "extension=pdo_sqlite" `
    -S "127.0.0.1:8000" `
    -t "public" `
    router.php
