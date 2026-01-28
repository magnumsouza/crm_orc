<?php

function service_all(PDO $pdo, bool $only_active = false): array
{
    $sql = 'SELECT * FROM services';
    $params = [];
    if ($only_active) {
        $sql .= " WHERE status = 'Ativo'";
    }
    $sql .= ' ORDER BY name ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function service_find(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([$id]);
    $service = $stmt->fetch();
    return $service ?: null;
}

function service_create(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare('INSERT INTO services (name, description, price, status) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
        $data['status'],
    ]);
    return (int)$pdo->lastInsertId();
}

function service_update(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE services SET name = ?, description = ?, price = ?, status = ? WHERE id = ?');
    $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
        $data['status'],
        $id,
    ]);
}

function service_delete(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM services WHERE id = ?');
    $stmt->execute([$id]);
}
