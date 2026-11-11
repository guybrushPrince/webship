<?php

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$root = dirname(__DIR__, 2);

$file = $root . $path;

if ($path !== '/' && file_exists($file)) {
    return false;
}

require __DIR__ . '/index.php';