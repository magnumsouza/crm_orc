<?php
require_once __DIR__ . '/../models/Service.php';

function services_index(): void
{
    $services = service_all(db());
    $service = [
        'name' => '',
        'description' => '',
        'price' => 0,
        'status' => 'Ativo',
    ];
    $flash = flash_get();
    include __DIR__ . '/../views/services/index.php';
}

function services_create(): void
{
    $service = null;
    $flash = flash_get();
    include __DIR__ . '/../views/services/form.php';
}

function services_store(): void
{
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $status = trim($_POST['status'] ?? 'Ativo');

    if ($name === '') {
        flash_set('error', 'Nome do servico e obrigatorio.');
        redirect('index.php?action=services_create');
    }

    if ($price < 0) {
        flash_set('error', 'Valor do servico invalido.');
        redirect('index.php?action=services_create');
    }

    service_create(db(), [
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'status' => $status === 'Inativo' ? 'Inativo' : 'Ativo',
    ]);

    flash_set('success', 'Servico cadastrado.');
    redirect('index.php?action=services');
}

function services_edit(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $service = service_find(db(), $id);
    if (!$service) {
        flash_set('error', 'Servico nao encontrado.');
        redirect('index.php?action=services');
    }
    $flash = flash_get();
    include __DIR__ . '/../views/services/form.php';
}

function services_update(): void
{
    $id = (int)($_POST['id'] ?? 0);
    $service = service_find(db(), $id);
    if (!$service) {
        flash_set('error', 'Servico nao encontrado.');
        redirect('index.php?action=services');
    }

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $status = trim($_POST['status'] ?? 'Ativo');

    if ($name === '') {
        flash_set('error', 'Nome do servico e obrigatorio.');
        redirect('index.php?action=services_edit&id=' . $id);
    }

    if ($price < 0) {
        flash_set('error', 'Valor do servico invalido.');
        redirect('index.php?action=services_edit&id=' . $id);
    }

    service_update(db(), $id, [
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'status' => $status === 'Inativo' ? 'Inativo' : 'Ativo',
    ]);

    flash_set('success', 'Servico atualizado.');
    redirect('index.php?action=services');
}

function services_delete(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $service = service_find(db(), $id);
    if (!$service) {
        flash_set('error', 'Servico nao encontrado.');
        redirect('index.php?action=services');
    }

    service_delete(db(), $id);
    flash_set('success', 'Servico removido.');
    redirect('index.php?action=services');
}
