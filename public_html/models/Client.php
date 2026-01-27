<?php

function client_all(PDO $pdo, string $search = ''): array
{
    if ($search) {
        $stmt = $pdo->prepare('SELECT * FROM clients WHERE name LIKE ? ORDER BY name');
        $stmt->execute(['%' . $search . '%']);
    } else {
        $stmt = $pdo->query('SELECT * FROM clients ORDER BY name');
    }
    return $stmt->fetchAll();
}

function client_find(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE id = ?');
    $stmt->execute([$id]);
    $client = $stmt->fetch();
    return $client ?: null;
}

function client_count(PDO $pdo): int
{
    $result = $pdo->query('SELECT COUNT(*) AS total FROM clients')->fetch();
    return (int)($result['total'] ?? 0);
}

function client_create(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare('INSERT INTO clients (name, email, phone, company, notes) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['company'],
        $data['notes'],
    ]);
    return (int)$pdo->lastInsertId();
}

function client_update(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE clients SET name = ?, email = ?, phone = ?, company = ?, notes = ? WHERE id = ?');
    $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['company'],
        $data['notes'],
        $id,
    ]);
}

function client_delete(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM clients WHERE id = ?');
    $stmt->execute([$id]);
}

function client_quotes(PDO $pdo, int $client_id): array
{
    $stmt = $pdo->prepare('SELECT * FROM quotes WHERE client_id = ? ORDER BY created_at DESC');
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();
}
