<?php

function product_all(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM products ORDER BY name');
    return $stmt->fetchAll();
}

function product_find(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    return $product ?: null;
}

function product_create(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare('INSERT INTO products (name, description, price) VALUES (?, ?, ?)');
    $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
    ]);
    return (int)$pdo->lastInsertId();
}

function product_update(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?');
    $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
        $id,
    ]);
}

function product_delete(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
}
