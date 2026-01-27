<?php

function invoice_all(PDO $pdo, string $type = ''): array
{
    $query = "SELECT i.*, c.name AS client_name_ref FROM invoices i LEFT JOIN clients c ON c.id = i.client_id";
    $params = [];
    if ($type !== '') {
        $query .= " WHERE i.type = ?";
        $params[] = $type;
    }
    $query .= " ORDER BY i.created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function invoice_find(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("SELECT i.*, c.name AS client_name_ref FROM invoices i LEFT JOIN clients c ON c.id = i.client_id WHERE i.id = ?");
    $stmt->execute([$id]);
    $invoice = $stmt->fetch();
    return $invoice ?: null;
}

function invoice_items(PDO $pdo, int $invoice_id): array
{
    $stmt = $pdo->prepare("SELECT ii.*, inv.name AS product_name FROM invoice_items ii LEFT JOIN inventory inv ON inv.id = ii.product_id WHERE ii.invoice_id = ?");
    $stmt->execute([$invoice_id]);
    return $stmt->fetchAll();
}

function invoice_create(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare("
        INSERT INTO invoices (schedule_id, client_id, type, mode, client_name, client_document, client_email, client_phone, client_address, total)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['schedule_id'],
        $data['client_id'],
        $data['type'],
        $data['mode'],
        $data['client_name'],
        $data['client_document'],
        $data['client_email'],
        $data['client_phone'],
        $data['client_address'],
        $data['total'],
    ]);
    return (int)$pdo->lastInsertId();
}

function invoice_add_item(PDO $pdo, int $invoice_id, array $item): void
{
    $stmt = $pdo->prepare("
        INSERT INTO invoice_items (invoice_id, product_id, description, quantity, unit_price, total_price)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $invoice_id,
        $item['product_id'],
        $item['description'],
        $item['quantity'],
        $item['unit_price'],
        $item['total_price'],
    ]);
}
