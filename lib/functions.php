<?php
// Core helpers and bootstrap (modernized autoload, keep session start)
error_reporting(E_ERROR | E_PARSE);
// For development you may set E_ALL & ~E_NOTICE

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

// Modern autoloader that preserves legacy underscore convention
spl_autoload_register(function ($class_name) {
    // Allow names like "Admin_User" -> lib/classes/Admin/Admin_User.php
    if (strpos($class_name, '_') !== false) {
        list($dir, $cl) = explode('_', $class_name, 2);
        $path = _SITE_ROOT . 'lib/classes/' . $dir . '/' . $class_name . '.php';
        if (is_file($path)) {
            include_once $path;
            return;
        }
    }

    // Common locations
    $paths = [
        _SITE_ROOT . 'lib/classes/' . $class_name . '.php',
        _SITE_ROOT . 'lib/classes/Front/' . $class_name . '.php',
        __DIR__ . '/classes/' . $class_name . '.php',
    ];
    foreach ($paths as $p) {
        if (is_file($p)) {
            include_once $p;
            return;
        }
    }
    // Silent fail: class may be optional
});

// Initialize database wrapper if configured
if (get_conf('use_database') == 1) {
    // ensure Database class exists (we provide lib/classes/Database.php)
    if (!isset($GLOBALS['db_instance'])) {
        $db = new Database;
    } else {
        $db = $GLOBALS['db_instance'];
    }
}
?>