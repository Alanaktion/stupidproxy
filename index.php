<?php
/*     __            _    __
  ___ / /___ _____  (_)__/ /__  _______ __ ____ __
 (_-</ __/ // / _ \/ / _  / _ \/ __/ _ \\ \ / // /
/___/\__/\_,_/ .__/_/\_,_/ .__/_/  \___/_\_\\_, /
            /_/         /_/                /___/*/
/**
 * @author Alan Hardman <alan@phpizza.com>
 *
 * @version 0.1.0
 *
 * @todo  Add non-cached POST request handling
 */

// Load configuration file before we begin
$config = require('config.php');
ignore_user_abort(true);

// We can start by getting the desired URL
$url = $config["prefix"] . $_SERVER['REQUEST_URI'];
$hash = sha1($url);
$file = __DIR__ . '/.cache/' . $hash;

// If a PURGE GET parameter is given, wipe any cache for this request
if(isset($_GET['PURGE'])) {
    if(is_file($file . '.b')) {
        unlink($file . '.h');
        unlink($file . '.b');
    }
}

// If a PURGEALL GET parameter is given, wipe all cache
if(isset($_GET['PURGEALL'])) {
    array_map('unlink', glob(".cache/*.h"));
    array_map('unlink', glob(".cache/*.b"));
}

// Serve a cached version, if any
$done = false;
if(is_file($file . '.b')) {
    $headers = file($file . '.h', FILE_IGNORE_NEW_LINES);
    foreach($headers as $h) {
        if(substr($h, 0, 12) == 'Connection:' || substr($h, 0, 16) == 'Content-Length:') {
            continue;
        }
        header($h, true);
    }
    $size = filesize($file . '.b');
    header("Connection: close\r\n");
    header("Content-Encoding: none\r\n", true);
    header("Content-Length: " . $size . "\r\n");
    readfile($file . '.b');
    flush();
    @fastcgi_finish_request();
    $done = true;
}

// Fetch the latest version and cache it
$options = array(
    'http' => array(
        'ignore_errors' => true
    )
);
$context = stream_context_create($options);
$body = file_get_contents($url, false, $context);
$headers = $http_response_header;
file_put_contents($file . '.h', implode("\n", $headers));
file_put_contents($file . '.b', $body);

// Serve the result if not already served from cache
if(!$done) {
    foreach($headers as $h) {
        if(substr($h, 0, 12) == 'Connection:' || substr($h, 0, 16) == 'Content-Length:') {
            continue;
        }
        header($h, true);
    }
    $size = strlen($body);
    echo $body;
}
