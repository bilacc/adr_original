<?php
// CSRF helpers and output escaping

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Return CSRF token (generate if missing).
 */
function csrf_token()
{
    if (empty($_SESSION['_csrf_token']) || empty($_SESSION['_csrf_token_time'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['_csrf_token_time'] = time();
    }
    return $_SESSION['_csrf_token'];
}

/**
 * Return HTML hidden input with CSRF token.
 */
function csrf_input()
{
    $token = csrf_token();
    return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verify CSRF token.
 * Accept tokens that are within $max_lifetime seconds (default 1 hour).
 */
function verify_csrf($token, $max_lifetime = 3600)
{
    if (empty($_SESSION['_csrf_token'])) return false;
    if (!hash_equals($_SESSION['_csrf_token'], $token)) return false;
    if (!empty($_SESSION['_csrf_token_time']) && (time() - $_SESSION['_csrf_token_time']) > $max_lifetime) return false;
    return true;
}

/**
 * Short helper for escaping output
 */
function e($s)
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
?>