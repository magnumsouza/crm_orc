<?php

function user_find_by_username(PDO $pdo, string $username): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function user_verify(PDO $pdo, string $username, string $password): ?array
{
    $user = user_find_by_username($pdo, $username);
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return null;
}
