<?php
require_once __DIR__ . '/../models/Client.php';

function clients_index(): void
{
    $search = trim($_GET['search'] ?? '');
    $clients = client_all(db(), $search);
    $flash = flash_get();
    include __DIR__ . '/../views/clients/index.php';
}

function clients_create(): void
{
    $client = ['name' => '', 'email' => '', 'phone' => '', 'company' => '', 'notes' => ''];
    $flash = flash_get();
    include __DIR__ . '/../views/clients/form.php';
}

function clients_store(): void
{
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'company' => trim($_POST['company'] ?? ''),
        'notes' => trim($_POST['notes'] ?? ''),
    ];

    if ($data['name'] === '' || $data['email'] === '') {
        flash_set('error', 'Preencha nome e email.');
        redirect('index.php?action=clients_create');
    }

    client_create(db(), $data);
    flash_set('success', 'Cliente cadastrado.');
    redirect('index.php?action=clients');
}

function clients_edit(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $client = client_find(db(), $id);
    if (!$client) {
        flash_set('error', 'Cliente nao encontrado.');
        redirect('index.php?action=clients');
    }
    $flash = flash_get();
    include __DIR__ . '/../views/clients/form.php';
}

function clients_update(): void
{
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'company' => trim($_POST['company'] ?? ''),
        'notes' => trim($_POST['notes'] ?? ''),
    ];

    client_update(db(), $id, $data);
    flash_set('success', 'Cliente atualizado.');
    redirect('index.php?action=clients');
}

function clients_delete(): void
{
    $id = (int)($_GET['id'] ?? 0);
    client_delete(db(), $id);
    flash_set('success', 'Cliente removido.');
    redirect('index.php?action=clients');
}

function clients_history(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $client = client_find(db(), $id);
    if (!$client) {
        flash_set('error', 'Cliente nao encontrado.');
        redirect('index.php?action=clients');
    }
    $quotes = client_quotes(db(), $id);
    $flash = flash_get();
    include __DIR__ . '/../views/clients/history.php';
}
