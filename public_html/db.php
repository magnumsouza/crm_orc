<?php
$config = require __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set($config['timezone']);

function db(): PDO
{
    static $pdo = null;
    if ($pdo) {
        return $pdo;
    }

    $config = require __DIR__ . '/config.php';
    $db = $config['db'];
    $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";

    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function base_url(string $path = ''): string
{
    $config = require __DIR__ . '/config.php';
    $base = trim($config['base_url'] ?? '');
    if ($base === '' || strtolower($base) === 'auto') {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $scheme = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
        $segments = array_filter(explode('/', trim($dir, '/')), 'strlen');
        $encoded = $segments ? '/' . implode('/', array_map('rawurlencode', $segments)) : '';
        $base = $scheme . '://' . $host . $encoded;
    }
    $base = rtrim($base, '/');
    $path = ltrim($path, '/');
    return $path ? $base . '/' . $path : $base;
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function current_user_role(): string
{
    return $_SESSION['user_role'] ?? 'viewer';
}

function is_admin(): bool
{
    return current_user_role() === 'admin';
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('index.php?action=login');
    }
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function money_br(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function phone_to_whatsapp(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone);
    if (!$digits) {
        return '';
    }
    if (strlen($digits) === 11 && substr($digits, 0, 2) !== '55') {
        $digits = '55' . $digits;
    }
    return $digits;
}
