<?php
// Central configuration accessor with optional local override.
// Do not put production secrets in this file in repositories.
function get_conf($conf_val)
{
    // default settings
    $conf = array();
    $conf['app_name'] = 'Adresar.net';
    $conf['email'] = 'info@adresar.net';
    $conf['use_database'] = 1;
    $conf['database_host'] = "localhost";
    $conf['database_name'] = "adr_local";
    $conf['database_username'] = "root";
    $conf['database_password'] = "";
    $conf['multi_language'] = 1;
    $conf['languages'] = array('hr','en');
    $conf['production'] = 1;
    $conf['routes'] = array();
    // Allow local overrides from lib/config.local.php (if present)
    if (is_file(__DIR__ . '/config.local.php')) {
        include __DIR__ . '/config.local.php';
        if (isset($LOCAL_CONFIG) && is_array($LOCAL_CONFIG)) {
            $conf = array_merge($conf, $LOCAL_CONFIG);
        }
    }
    return isset($conf[$conf_val]) ? $conf[$conf_val] : null;
}

// Site constants (safe runtime detection)
define('_SITE_TITLE', 'Adresar Nekretnine');

$site_directory = '/';
if (isset($_SERVER['DOCUMENT_ROOT']) && isset($_SERVER['SCRIPT_FILENAME']) && strpos($_SERVER['SCRIPT_FILENAME'], '/htdocs/') !== false) {
    // historical path case
    $site_directory = '/Tomislav/VirtusAdmin/';
}
define('_SITE_DIRECTORY', $site_directory);
define('_SITE_ROOT', rtrim($_SERVER["DOCUMENT_ROOT"], '/\\') . _SITE_DIRECTORY);

// Basic domain and url
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
define('_SITE_URL', $protocol . '://' . $serverName . _SITE_DIRECTORY);

// derive domain safely
$parsed = parse_url(_SITE_URL);
define('_SITE_DOMAIN', isset($parsed['host']) ? $parsed['host'] : $serverName);

define('_PHOTOS_URL', _SITE_URL . 'slike');
define('_FIRMA_EMAIL', 'info@adresar.net');
define('_FIRMA_NAZIV', 'Adresar.net');

// small defaults
define('_PDV', 25);
define('_STORE_COOKIE_NAME', 'sp_store');
define('_STORE_SALT', 'ys#4se');
?>
