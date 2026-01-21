<?php
require_once __DIR__ . '/../models/User.php';

function show_login(): void
{
    $flash = flash_get();
    include __DIR__ . '/../views/login.php';
}

function handle_login(): void
{
    $pdo = db();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = user_verify($pdo, $username, $password);
    if (!$user) {
        flash_set('error', 'Usuario ou senha invalidos.');
        redirect('index.php?action=login');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    flash_set('success', 'Bem-vindo!');
    redirect('index.php');
}

function handle_logout(): void
{
    session_destroy();
    redirect('index.php?action=login');
}
