<?php
require_once __DIR__ . '/../models/User.php';

function show_login(): void
{
    $flash = flash_get();
    $google_config = require __DIR__ . '/../config.oauth.php';
    $google_config = $google_config['google'];
    $google_login_url = build_google_auth_url($google_config);
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
    $role = $user['role'] ?? '';
    if ($role === '' && ($user['username'] ?? '') === 'admin') {
        $role = 'admin';
    }
    $_SESSION['user_role'] = $role !== '' ? $role : 'viewer';
    flash_set('success', 'Bem-vindo!');
    redirect('index.php');
}

function handle_logout(): void
{
    session_destroy();
    redirect('index.php?action=login');
}

function build_google_auth_url(array $config): string
{
    $auth_uri = 'https://accounts.google.com/o/oauth2/v2/auth';
    $params = [
        'client_id' => $config['client_id'],
        'redirect_uri' => $config['redirect_uri'],
        'response_type' => 'code',
        'scope' => implode(' ', $config['scopes']),
        'access_type' => 'offline',
        'prompt' => 'consent',
    ];
    return $auth_uri . '?' . http_build_query($params);
}

function handle_google_callback(): void
{
    $google_config = require __DIR__ . '/../config.oauth.php';
    $google_config = $google_config['google'];
    
    $code = $_GET['code'] ?? '';
    
    if (empty($code)) {
        flash_set('error', 'Erro na autenticação com Google.');
        redirect('index.php?action=login');
    }
    
    // Trocar código por token
    $token_data = exchange_code_for_token($code, $google_config);
    
    if (!$token_data || empty($token_data['access_token'])) {
        flash_set('error', 'Erro ao obter token do Google.');
        redirect('index.php?action=login');
    }
    
    // Obter informações do usuário
    $user_info = get_google_user_info($token_data['access_token']);
    
    if (!$user_info || empty($user_info['email'])) {
        flash_set('error', 'Erro ao obter dados do usuário.');
        redirect('index.php?action=login');
    }
    
    // Criar ou atualizar usuário no banco
    $pdo = db();
    $user = authenticate_google_user($pdo, $user_info);
    
    if (!$user) {
        flash_set('error', 'Erro ao registrar usuário.');
        redirect('index.php?action=login');
    }
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'] ?? 'viewer';
    $_SESSION['google_auth'] = true;
    flash_set('success', 'Bem-vindo ' . htmlspecialchars($user['name']) . '!');
    redirect('index.php');
}

function exchange_code_for_token(string $code, array $config): ?array
{
    $token_uri = 'https://oauth2.googleapis.com/token';
    
    $post_data = [
        'code' => $code,
        'client_id' => $config['client_id'],
        'client_secret' => $config['client_secret'],
        'redirect_uri' => $config['redirect_uri'],
        'grant_type' => 'authorization_code',
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $token_uri,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($post_data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if (!$response) {
        return null;
    }
    
    return json_decode($response, true);
}

function get_google_user_info(string $access_token): ?array
{
    $userinfo_uri = 'https://www.googleapis.com/oauth2/v2/userinfo';
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $userinfo_uri . '?access_token=' . urlencode($access_token),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if (!$response) {
        return null;
    }
    
    return json_decode($response, true);
}

function authenticate_google_user(PDO $pdo, array $user_info): ?array
{
    // Procurar usuário por email
    $stmt = $pdo->prepare('SELECT id, name, email, role FROM users WHERE email = ?');
    $stmt->execute([$user_info['email']]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Atualizar último acesso
        $update = $pdo->prepare('UPDATE users SET updated_at = NOW() WHERE id = ?');
        $update->execute([$user['id']]);
        return $user;
    }
    
    // Criar novo usuário
    $name = $user_info['name'] ?? explode('@', $user_info['email'])[0];
    $insert = $pdo->prepare('INSERT INTO users (email, name, role, created_at) VALUES (?, ?, ?, NOW())');
    
    if ($insert->execute([$user_info['email'], $name, 'viewer'])) {
        $id = (int)$pdo->lastInsertId();
        return [
            'id' => $id,
            'email' => $user_info['email'],
            'name' => $name,
            'role' => 'viewer',
        ];
    }
    
    return null;
}
