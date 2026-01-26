<?php
require_once __DIR__ . '/../models/Inventory.php';

function inventory_index(): void
{
    $search = trim($_GET['search'] ?? '');
    $items = inventory_all(db(), $search);
    $stats = inventory_stats(db());
    $low_stock = inventory_low_stock(db());
    $item = [
        'sku' => '',
        'name' => '',
        'category' => '',
        'description' => '',
        'quantity' => 0,
        'price' => 0,
        'cost' => 0,
        'min_quantity' => 5,
        'max_quantity' => 100,
    ];
    $flash = flash_get();
    include __DIR__ . '/../views/inventory/index.php';
}

function inventory_create(): void
{
    $item = null; // Para criar novo, item é null
    $stats = inventory_stats(db());
    $flash = flash_get();
    include __DIR__ . '/../views/inventory/form.php';
}

function inventory_store(): void
{
    $pdo = db();
    $sku = trim($_POST['sku'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $cost = (float)($_POST['cost'] ?? 0);
    $min_quantity = (int)($_POST['min_quantity'] ?? 5);
    $max_quantity = (int)($_POST['max_quantity'] ?? 100);

    // Validações
    if (empty($sku)) {
        flash_set('error', 'SKU é obrigatório.');
        redirect('index.php?action=inventory_create');
    }

    if (empty($name)) {
        flash_set('error', 'Nome do produto é obrigatório.');
        redirect('index.php?action=inventory_create');
    }

    if ($price <= 0) {
        flash_set('error', 'Preço deve ser maior que zero.');
        redirect('index.php?action=inventory_create');
    }

    if ($cost <= 0) {
        flash_set('error', 'Custo deve ser maior que zero.');
        redirect('index.php?action=inventory_create');
    }

    // Verificar se SKU já existe
    if (inventory_by_sku($pdo, $sku)) {
        flash_set('error', 'SKU já existe no sistema.');
        redirect('index.php?action=inventory_create');
    }

    $item_id = inventory_insert($pdo, [
        'sku' => $sku,
        'name' => $name,
        'category' => $category,
        'description' => $description,
        'quantity' => $quantity,
        'price' => $price,
        'cost' => $cost,
        'min_quantity' => $min_quantity,
        'max_quantity' => $max_quantity,
        'status' => 'Ativo',
    ]);

    flash_set('success', 'Produto adicionado ao estoque com sucesso!');
    redirect('index.php?action=inventory_view&id=' . $item_id);
}

function inventory_view(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $item = inventory_find(db(), $id);
    
    if (!$item) {
        flash_set('error', 'Produto não encontrado.');
        redirect('index.php?action=inventory');
    }

    $flash = flash_get();
    include __DIR__ . '/../views/inventory/view.php';
}

function inventory_edit(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $item = inventory_find(db(), $id);
    
    if (!$item) {
        flash_set('error', 'Produto não encontrado.');
        redirect('index.php?action=inventory');
    }

    $stats = inventory_stats(db());
    $flash = flash_get();
    include __DIR__ . '/../views/inventory/form.php';
}

function inventory_update(): void
{
    $pdo = db();
    $id = (int)($_POST['id'] ?? 0);
    $sku = trim($_POST['sku'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $cost = (float)($_POST['cost'] ?? 0);
    $min_quantity = (int)($_POST['min_quantity'] ?? 5);
    $max_quantity = (int)($_POST['max_quantity'] ?? 100);

    $item = inventory_find($pdo, $id);
    if (!$item) {
        flash_set('error', 'Produto não encontrado.');
        redirect('index.php?action=inventory');
    }

    // Validações
    if (empty($sku)) {
        flash_set('error', 'SKU é obrigatório.');
        redirect('index.php?action=inventory_edit&id=' . $id);
    }

    if (empty($name)) {
        flash_set('error', 'Nome do produto é obrigatório.');
        redirect('index.php?action=inventory_edit&id=' . $id);
    }

    if ($price <= 0) {
        flash_set('error', 'Preço deve ser maior que zero.');
        redirect('index.php?action=inventory_edit&id=' . $id);
    }

    if ($cost <= 0) {
        flash_set('error', 'Custo deve ser maior que zero.');
        redirect('index.php?action=inventory_edit&id=' . $id);
    }

    // Verificar se novo SKU já existe (sem ser o atual)
    $existing = inventory_by_sku($pdo, $sku);
    if ($existing && $existing['id'] !== $id) {
        flash_set('error', 'SKU já existe no sistema.');
        redirect('index.php?action=inventory_edit&id=' . $id);
    }

    inventory_do_update($pdo, $id, [
        'sku' => $sku,
        'name' => $name,
        'category' => $category,
        'description' => $description,
        'quantity' => $quantity,
        'price' => $price,
        'cost' => $cost,
        'min_quantity' => $min_quantity,
        'max_quantity' => $max_quantity,
        'status' => 'Ativo',
    ]);

    flash_set('success', 'Produto atualizado com sucesso!');
    redirect('index.php?action=inventory_view&id=' . $id);
}

function inventory_delete(): void
{
    $id = (int)($_GET['id'] ?? 0);
    
    if (inventory_do_delete(db(), $id)) {
        flash_set('success', 'Produto removido do estoque.');
    } else {
        flash_set('error', 'Erro ao remover produto.');
    }

    redirect('index.php?action=inventory');
}

?>
