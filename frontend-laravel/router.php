<?php

$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$publicPath = __DIR__ . '/public';
$requestedFile = realpath($publicPath . $requestUri);

if ($requestUri !== '/' && $requestedFile && str_starts_with($requestedFile, realpath($publicPath)) && is_file($requestedFile)) {
    $extension = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));
    $contentTypes = [
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
    ];

    header('Content-Type: '.($contentTypes[$extension] ?? 'application/octet-stream'));
    header('Content-Length: '.filesize($requestedFile));
    readfile($requestedFile);
    return true;
}

require $publicPath . '/index.php';
