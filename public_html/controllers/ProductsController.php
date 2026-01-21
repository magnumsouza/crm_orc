<?php
require_once __DIR__ . '/../models/Product.php';

function products_index(): void
{
    $products = product_all(db());
    $flash = flash_get();
    include __DIR__ . '/../views/products/index.php';
}

function products_create(): void
{
    $product = ['name' => '', 'description' => '', 'price' => ''];
    $flash = flash_get();
    include __DIR__ . '/../views/products/form.php';
}

function products_store(): void
{
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'price' => (float)str_replace(',', '.', $_POST['price'] ?? 0),
    ];

    if ($data['name'] === '' || $data['price'] <= 0) {
        flash_set('error', 'Preencha nome e preco valido.');
        redirect('index.php?action=products_create');
    }

    product_create(db(), $data);
    flash_set('success', 'Produto cadastrado.');
    redirect('index.php?action=products');
}

function products_edit(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $product = product_find(db(), $id);
    if (!$product) {
        flash_set('error', 'Produto nao encontrado.');
        redirect('index.php?action=products');
    }
    $flash = flash_get();
    include __DIR__ . '/../views/products/form.php';
}

function products_update(): void
{
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'price' => (float)str_replace(',', '.', $_POST['price'] ?? 0),
    ];

    product_update(db(), $id, $data);
    flash_set('success', 'Produto atualizado.');
    redirect('index.php?action=products');
}

function products_delete(): void
{
    $id = (int)($_GET['id'] ?? 0);
    product_delete(db(), $id);
    flash_set('success', 'Produto removido.');
    redirect('index.php?action=products');
}
