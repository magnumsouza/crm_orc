<?php

function quote_counts(PDO $pdo): array
{
    $total = $pdo->query('SELECT COUNT(*) AS total FROM quotes')->fetch()['total'] ?? 0;
    $approved = $pdo->query("SELECT COUNT(*) AS total FROM quotes WHERE status = 'Aprovado'")->fetch()['total'] ?? 0;
    $rejected = $pdo->query("SELECT COUNT(*) AS total FROM quotes WHERE status = 'Recusado'")->fetch()['total'] ?? 0;
    return [
        'total' => (int)$total,
        'approved' => (int)$approved,
        'rejected' => (int)$rejected,
    ];
}

function quote_all(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT q.*, c.name AS client_name FROM quotes q JOIN clients c ON c.id = q.client_id ORDER BY q.created_at DESC');
    return $stmt->fetchAll();
}

function quote_find(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT q.*, c.name AS client_name, c.phone AS client_phone, c.email AS client_email, c.company AS client_company FROM quotes q JOIN clients c ON c.id = q.client_id WHERE q.id = ?');
    $stmt->execute([$id]);
    $quote = $stmt->fetch();
    return $quote ?: null;
}

function quote_items(PDO $pdo, int $quote_id): array
{
    $stmt = $pdo->prepare('
        SELECT qi.*,
               i.name AS product_name,
               i.description AS product_description,
               s.name AS service_name,
               s.description AS service_description
        FROM quote_items qi
        LEFT JOIN inventory i ON i.id = qi.inventory_id
        LEFT JOIN services s ON s.id = qi.service_id
        WHERE qi.quote_id = ?
    ');
    $stmt->execute([$quote_id]);
    return $stmt->fetchAll();
}

function quote_create(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare('INSERT INTO quotes (client_id, status, notes, total) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $data['client_id'],
        $data['status'],
        $data['notes'],
        $data['total'],
    ]);
    return (int)$pdo->lastInsertId();
}

function quote_add_item(PDO $pdo, int $quote_id, array $item): void
{
    $stmt = $pdo->prepare('
        INSERT INTO quote_items (quote_id, inventory_id, service_id, item_type, description, quantity, unit_price, total_price)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $quote_id,
        $item['inventory_id'],
        $item['service_id'],
        $item['item_type'],
        $item['description'],
        $item['quantity'],
        $item['unit_price'],
        $item['total_price'],
    ]);
}

function quote_update(PDO $pdo, int $quote_id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE quotes SET client_id = ?, notes = ?, total = ? WHERE id = ?');
    $stmt->execute([
        $data['client_id'],
        $data['notes'],
        $data['total'],
        $quote_id,
    ]);
}

function quote_clear_items(PDO $pdo, int $quote_id): void
{
    $stmt = $pdo->prepare('DELETE FROM quote_items WHERE quote_id = ?');
    $stmt->execute([$quote_id]);
}

function quote_delete(PDO $pdo, int $quote_id): void
{
    $stmt = $pdo->prepare('DELETE FROM quotes WHERE id = ?');
    $stmt->execute([$quote_id]);
}

function quote_update_status(PDO $pdo, int $quote_id, string $status): void
{
    $stmt = $pdo->prepare('UPDATE quotes SET status = ? WHERE id = ?');
    $stmt->execute([$status, $quote_id]);
}

function quote_update_pdf_path(PDO $pdo, int $quote_id, string $pdf_path): void
{
    $stmt = $pdo->prepare('UPDATE quotes SET pdf_path = ? WHERE id = ?');
    $stmt->execute([$pdf_path, $quote_id]);
}
