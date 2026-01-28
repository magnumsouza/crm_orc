<?php

function cashbox_add_entry(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO cashbox_entries (type, origin, description, quantity, amount, reference_type, reference_id)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $data['type'],
        $data['origin'],
        $data['description'],
        $data['quantity'],
        $data['amount'],
        $data['reference_type'],
        $data['reference_id'],
    ]);
}

function cashbox_all(PDO $pdo, int $limit = 200): array
{
    $stmt = $pdo->prepare('SELECT * FROM cashbox_entries ORDER BY created_at DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function cashbox_totals(PDO $pdo): array
{
    $stmt = $pdo->query(
        "SELECT
            SUM(CASE WHEN type = 'entrada' THEN amount ELSE 0 END) AS total_entrada,
            SUM(CASE WHEN type = 'saida' THEN amount ELSE 0 END) AS total_saida
         FROM cashbox_entries"
    );
    $row = $stmt->fetch();
    return [
        'entrada' => (float)($row['total_entrada'] ?? 0),
        'saida' => (float)($row['total_saida'] ?? 0),
    ];
}
