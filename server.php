<?php

/*
 * Router for `php artisan serve`, which picks up this file instead of Laravel's default one.
 *
 * PHP's built-in server sends public files with no caching headers, so on the event WLAN every
 * tablet downloaded every candidate photo again on every page. Public files are sent from here
 * with Last-Modified/ETag (so repeat requests get an empty 304) and a max-age:
 *   - /build/assets/* is content-hashed by Vite, so it is cached for a year.
 *   - URLs with ?v= (candidate photos, versioned by file time) are cached for a day.
 *   - everything else is cached for 5 minutes, then revalidated.
 */

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$types = [
    'css' => 'text/css; charset=UTF-8',
    'js' => 'text/javascript; charset=UTF-8',
    'json' => 'application/json',
    'map' => 'application/json',
    'woff2' => 'font/woff2',
    'woff' => 'font/woff',
    'ttf' => 'font/ttf',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
    'txt' => 'text/plain; charset=UTF-8',
];

if ($uri !== '/' && file_exists($publicPath.$uri)) {
    $file = realpath($publicPath.$uri);
    $type = $types[strtolower(pathinfo($uri, PATHINFO_EXTENSION))] ?? null;

    // Anything we don't recognise, or that resolves outside public/, goes to the built-in server as before.
    if (! $file || ! is_file($file) || ! $type || ! str_starts_with($file, realpath($publicPath).DIRECTORY_SEPARATOR)) {
        return false;
    }

    $modified = filemtime($file);
    $etag = sprintf('"%x-%x"', $modified, filesize($file));

    $maxAge = match (true) {
        str_starts_with($uri, '/build/assets/') => '31536000, immutable',
        isset($_GET['v']) => '86400',
        default => '300',
    };

    header("Content-Type: {$type}");
    header('Cache-Control: public, max-age='.$maxAge);
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $modified).' GMT');
    header("ETag: {$etag}");

    $ifNoneMatch = $_SERVER['HTTP_IF_NONE_MATCH'] ?? null;
    $ifModifiedSince = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) : false;

    if ($ifNoneMatch === $etag || (! $ifNoneMatch && $ifModifiedSince !== false && $ifModifiedSince >= $modified)) {
        http_response_code(304);

        return true;
    }

    header('Content-Length: '.filesize($file));

    if ($_SERVER['REQUEST_METHOD'] !== 'HEAD') {
        readfile($file);
    }

    return true;
}

$formattedDateTime = date('D M j H:i:s Y');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$remoteAddress = $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'];

file_put_contents('php://stdout', "[$formattedDateTime] $remoteAddress [$requestMethod] URI: $uri\n");

require_once $publicPath.'/index.php';
