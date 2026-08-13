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

    throw "PHP executable tidak ditemukan. Pastikan PHP Laragon tersedia atau sudah masuk PATH."
}

$phpPath = Get-PhpExecutable
$extensionDir = Join-Path (Split-Path $phpPath -Parent) "ext"

if (-not (Test-Path -LiteralPath $extensionDir)) {
    throw "Folder extension PHP tidak ditemukan: $extensionDir"
}

& $phpPath `
    -d "extension_dir=$extensionDir" `
    -d "extension=sqlite3" `
    -d "extension=pdo_sqlite" `
    -S "127.0.0.1:8000" `
    -t "public" `
    router.php
