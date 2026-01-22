<?php

function inventory_all(PDO $pdo, string $search = ''): array
{
    $query = "SELECT * FROM inventory WHERE status = 'Ativo'";
    
    if (!empty($search)) {
        $query .= " AND (name LIKE ? OR sku LIKE ? OR category LIKE ?)";
        $stmt = $pdo->prepare($query . " ORDER BY name ASC");
        $search_term = "%$search%";
        $stmt->execute([$search_term, $search_term, $search_term]);
    } else {
        $stmt = $pdo->prepare($query . " ORDER BY name ASC");
        $stmt->execute();
    }
    
    return $stmt->fetchAll();
}

function inventory_find(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    return $result ?: null;
}

function inventory_by_sku(PDO $pdo, string $sku): ?array
{
    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE sku = ?");
    $stmt->execute([$sku]);
    $result = $stmt->fetch();
    return $result ?: null;
}

function inventory_insert(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare("
        INSERT INTO inventory (sku, name, category, description, quantity, price, cost, min_quantity, max_quantity, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['sku'],
        $data['name'],
        $data['category'],
        $data['description'],
        $data['quantity'],
        $data['price'],
        $data['cost'],
        $data['min_quantity'],
        $data['max_quantity'],
        $data['status'],
    ]);
    return (int)$pdo->lastInsertId();
}

function inventory_do_update(PDO $pdo, int $id, array $data): bool
{
    $stmt = $pdo->prepare("
        UPDATE inventory SET 
            sku = ?,
            name = ?,
            category = ?,
            description = ?,
            quantity = ?,
            price = ?,
            cost = ?,
            min_quantity = ?,
            max_quantity = ?,
            status = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    return $stmt->execute([
        $data['sku'],
        $data['name'],
        $data['category'],
        $data['description'],
        $data['quantity'],
        $data['price'],
        $data['cost'],
        $data['min_quantity'],
        $data['max_quantity'],
        $data['status'],
        $id,
    ]);
}

function inventory_do_delete(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare("UPDATE inventory SET status = 'Inativo' WHERE id = ?");
    return $stmt->execute([$id]);
}

function inventory_adjust_quantity(PDO $pdo, int $id, int $adjustment): bool
{
    $stmt = $pdo->prepare("UPDATE inventory SET quantity = quantity + ?, updated_at = NOW() WHERE id = ?");
    return $stmt->execute([$adjustment, $id]);
}

function inventory_stats(PDO $pdo): array
{
    $total = $pdo->query("SELECT COUNT(*) as cnt FROM inventory WHERE status = 'Ativo'")->fetch()['cnt'];
    $low_stock = $pdo->query("SELECT COUNT(*) as cnt FROM inventory WHERE status = 'Ativo' AND quantity < min_quantity")->fetch()['cnt'];
    $total_value = $pdo->query("SELECT SUM(quantity * cost) as total FROM inventory WHERE status = 'Ativo'")->fetch()['total'] ?? 0;
    $categories = $pdo->query("SELECT DISTINCT category FROM inventory WHERE status = 'Ativo' ORDER BY category")->fetchAll();
    
    return [
        'total_items' => $total,
        'low_stock_count' => $low_stock,
        'total_value' => (float)$total_value,
        'categories' => $categories,
    ];
}

function inventory_by_category(PDO $pdo, string $category): array
{
    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE category = ? AND status = 'Ativo' ORDER BY name ASC");
    $stmt->execute([$category]);
    return $stmt->fetchAll();
}

function inventory_low_stock(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT * FROM inventory WHERE status = 'Ativo' AND quantity < min_quantity ORDER BY quantity ASC");
    return $stmt->fetchAll();
}

?>
