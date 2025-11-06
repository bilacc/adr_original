<?php
// Minimal thumb script: src can be absolute URL or path. Supports ?w=&h=&zc=
if (!isset($_GET['src'])) {
    header("HTTP/1.1 400 Bad Request");
    exit('Missing src');
}
$src = $_GET['src'];
// If src contains site url, strip it
$siteUrl = rtrim((isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:'http').'://'.$_SERVER['HTTP_HOST'] . '/', '/');
$src = str_replace($siteUrl, '', $src);
$src = ltrim($src, '/');
$file = __DIR__ . '/../../' . $src; // go up to project root
if (!is_file($file)) {
    header("HTTP/1.1 404 Not Found");
    exit('File not found');
}
// For now, do not resize: just serve original file with proper headers.
// You can enhance using GD to implement w/h & zc parameters.
$mime = mime_content_type($file);
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($file));
readfile($file);
exit;
?>
