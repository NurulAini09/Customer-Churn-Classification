$phpPath = "C:\laragon\bin\php\php-8.3.15-nts-Win32-vs16-x64\php.exe"
$extensionDir = "C:\laragon\bin\php\php-8.3.15-nts-Win32-vs16-x64\ext"

& $phpPath `
    -d "extension_dir=$extensionDir" `
    -d "extension=sqlite3" `
    -d "extension=pdo_sqlite" `
    artisan migrate --force
