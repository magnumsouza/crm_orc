<?php
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Inventory.php';
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../services/WhatsAppService.php';

function schedules_bootstrap_whatsapp(): void
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $config_path = __DIR__ . '/../config.whatsapp.php';
    if (file_exists($config_path)) {
        require_once $config_path;
    }
    $loaded = true;
}

function schedules_index(): void
{
    $search = trim($_GET['q'] ?? '');
    $schedules = schedule_all(db(), $search);
    $counts = schedule_counts(db());
    $clients = [];
    $inventory = [];
    $settings = [];
    $approved_quotes = [];
    if (is_admin()) {
        $clients = client_all(db());
        $inventory = inventory_all(db());
        $settings = schedule_get_settings(db());
        $approved_quotes = quote_approved_with_items(db());
    }
    $flash = flash_get();
    include __DIR__ . '/../views/schedules/index.php';
}

function schedules_create(): void
{
    $clients = client_all(db());
    $inventory = inventory_all(db());
    $settings = schedule_get_settings(db());
    $approved_quotes = quote_approved_with_items(db());
    $flash = flash_get();
    $schedule = null;
    $items = [];
    include __DIR__ . '/../views/schedules/form.php';
}

function schedules_store(): void
{
    $pdo = db();
    $client_id = (int)($_POST['client_id'] ?? 0);
    $quote_id = (int)($_POST['quote_id'] ?? 0);
    $service_description = trim($_POST['service_description'] ?? '');
    $scheduled_date = trim($_POST['scheduled_date'] ?? '');
    $scheduled_time = schedule_normalize_time(trim($_POST['scheduled_time'] ?? ''));
    $notes = trim($_POST['notes'] ?? '');
    $items = $_POST['items'] ?? [];

    if ($quote_id > 0 && !quote_is_approved($pdo, $quote_id)) {
        $quote_id = 0;
    }
    if ($quote_id > 0) {
        $quote = quote_find($pdo, $quote_id);
        if ($quote) {
            $client_id = (int)$quote['client_id'];
            if ($service_description === '') {
                $service_description = 'Orcamento #' . $quote_id;
            }
            if (empty($items)) {
                $items = schedule_items_from_quote(quote_items($pdo, $quote_id));
            }
        }
    }

    if ($client_id <= 0 || $service_description === '' || $scheduled_date === '' || $scheduled_time === '') {
        flash_set('error', 'Preencha cliente, servico, data e horario.');
        redirect('index.php?action=schedules_create');
    }

    if (!schedule_is_valid_datetime($pdo, $scheduled_date, $scheduled_time)) {
        flash_set('error', 'Data ou horario invalido para o horario comercial.');
        redirect('index.php?action=schedules_create');
    }

    if (!schedule_is_slot_available($pdo, $scheduled_date, $scheduled_time)) {
        flash_set('error', 'Horario ja ocupado.');
        redirect('index.php?action=schedules_create');
    }

    $errors = [];
    $parsed_items = schedule_parse_items($pdo, $items, $errors);
    if (!empty($errors)) {
        flash_set('error', $errors[0]);
        redirect('index.php?action=schedules_create');
    }

    $schedule_id = schedule_create($pdo, [
        'client_id' => $client_id,
        'quote_id' => $quote_id ?: null,
        'service_description' => $service_description,
        'scheduled_date' => $scheduled_date,
        'scheduled_time' => $scheduled_time,
        'status' => 'Agendado',
        'notes' => $notes,
    ]);

    if (!empty($parsed_items)) {
        schedule_replace_items($pdo, $schedule_id, $parsed_items);
    }

    $client = client_find($pdo, $client_id);
    if ($client) {
        schedules_bootstrap_whatsapp();
        WhatsAppService::notifyNewSchedule($client, [
            'scheduled_date' => $scheduled_date,
            'scheduled_time' => $scheduled_time,
            'service_description' => $service_description,
        ]);
    }

    flash_set('success', 'Agendamento criado com sucesso.');
    redirect('index.php?action=schedules_view&id=' . $schedule_id);
}

function schedules_view(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $schedule = schedule_find(db(), $id);
    if (!$schedule) {
        flash_set('error', 'Agendamento nao encontrado.');
        redirect('index.php?action=schedules');
    }
    $items = schedule_items(db(), $id);
    $flash = flash_get();
    include __DIR__ . '/../views/schedules/view.php';
}

function schedules_edit(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $schedule = schedule_find(db(), $id);
    if (!$schedule) {
        flash_set('error', 'Agendamento nao encontrado.');
        redirect('index.php?action=schedules');
    }
    if ($schedule['status'] === 'Concluido' && (int)$schedule['inventory_applied'] === 1) {
        flash_set('error', 'Agendamentos concluidos nao podem ser editados.');
        redirect('index.php?action=schedules_view&id=' . $id);
    }
    $clients = client_all(db());
    $inventory = inventory_all(db());
    $settings = schedule_get_settings(db());
    $approved_quotes = quote_approved_with_items(db());
    $items = schedule_items(db(), $id);
    $flash = flash_get();
    include __DIR__ . '/../views/schedules/form.php';
}

function schedules_update(): void
{
    $pdo = db();
    $id = (int)($_POST['id'] ?? 0);
    $schedule = schedule_find($pdo, $id);
    if (!$schedule) {
        flash_set('error', 'Agendamento nao encontrado.');
        redirect('index.php?action=schedules');
    }
    if ($schedule['status'] === 'Concluido' && (int)$schedule['inventory_applied'] === 1) {
        flash_set('error', 'Agendamentos concluidos nao podem ser editados.');
        redirect('index.php?action=schedules_view&id=' . $id);
    }

    $client_id = (int)($_POST['client_id'] ?? 0);
    $quote_id = (int)($_POST['quote_id'] ?? 0);
    $service_description = trim($_POST['service_description'] ?? '');
    $scheduled_date = trim($_POST['scheduled_date'] ?? '');
    $scheduled_time = schedule_normalize_time(trim($_POST['scheduled_time'] ?? ''));
    $notes = trim($_POST['notes'] ?? '');
    $items = $_POST['items'] ?? [];

    if ($quote_id > 0 && !quote_is_approved($pdo, $quote_id)) {
        $quote_id = 0;
    }
    if ($quote_id > 0) {
        $quote = quote_find($pdo, $quote_id);
        if ($quote) {
            $client_id = (int)$quote['client_id'];
            if ($service_description === '') {
                $service_description = 'Orcamento #' . $quote_id;
            }
            if (empty($items)) {
                $items = schedule_items_from_quote(quote_items($pdo, $quote_id));
            }
        }
    }

    if ($client_id <= 0 || $service_description === '' || $scheduled_date === '' || $scheduled_time === '') {
        flash_set('error', 'Preencha cliente, servico, data e horario.');
        redirect('index.php?action=schedules_edit&id=' . $id);
    }

    if (!schedule_is_valid_datetime($pdo, $scheduled_date, $scheduled_time)) {
        flash_set('error', 'Data ou horario invalido para o horario comercial.');
        redirect('index.php?action=schedules_edit&id=' . $id);
    }

    if (!schedule_is_slot_available($pdo, $scheduled_date, $scheduled_time, $id)) {
        flash_set('error', 'Horario ja ocupado.');
        redirect('index.php?action=schedules_edit&id=' . $id);
    }

    $errors = [];
    $parsed_items = schedule_parse_items($pdo, $items, $errors);
    if (!empty($errors)) {
        flash_set('error', $errors[0]);
        redirect('index.php?action=schedules_edit&id=' . $id);
    }

    schedule_update($pdo, $id, [
        'client_id' => $client_id,
        'quote_id' => $quote_id ?: null,
        'service_description' => $service_description,
        'scheduled_date' => $scheduled_date,
        'scheduled_time' => $scheduled_time,
        'notes' => $notes,
    ]);

    schedule_replace_items($pdo, $id, $parsed_items);

    $client = client_find($pdo, $client_id);
    if ($client) {
        schedules_bootstrap_whatsapp();
        WhatsAppService::notifyScheduleUpdate($client, [
            'scheduled_date' => $scheduled_date,
            'scheduled_time' => $scheduled_time,
        ]);
    }

    flash_set('success', 'Agendamento atualizado.');
    redirect('index.php?action=schedules_view&id=' . $id);
}

function schedules_delete(): void
{
    $pdo = db();
    $id = (int)($_GET['id'] ?? 0);
    $schedule = schedule_find($pdo, $id);
    if (!$schedule) {
        flash_set('error', 'Agendamento nao encontrado.');
        redirect('index.php?action=schedules');
    }

    $status_result = schedule_apply_status($pdo, $schedule, 'Cancelado');
    if (!$status_result['ok']) {
        flash_set('error', $status_result['message']);
        redirect('index.php?action=schedules_view&id=' . $id);
    }

    flash_set('success', 'Agendamento cancelado.');
    redirect('index.php?action=schedules');
}

function schedules_update_status(): void
{
    $pdo = db();
    $id = (int)($_POST['id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    $schedule = schedule_find($pdo, $id);
    if (!$schedule) {
        flash_set('error', 'Agendamento nao encontrado.');
        redirect('index.php?action=schedules');
    }

    $status_result = schedule_apply_status($pdo, $schedule, $status);
    if (!$status_result['ok']) {
        flash_set('error', $status_result['message']);
        redirect('index.php?action=schedules_view&id=' . $id);
    }

    flash_set('success', 'Status atualizado.');
    redirect('index.php?action=schedules_view&id=' . $id);
}

function schedules_api_slots(): void
{
    $date = trim($_GET['date'] ?? '');
    if ($date === '') {
        header('Content-Type: application/json');
        echo json_encode(['slots' => []]);
        return;
    }
    $slots = schedule_get_available_slots(db(), $date);
    header('Content-Type: application/json');
    echo json_encode(['slots' => $slots]);
}

function schedule_parse_items(PDO $pdo, array $items, array &$errors): array
{
    $parsed = [];
    foreach ($items as $item) {
        $inventory_id = (int)($item['inventory_id'] ?? 0);
        $quantity = (int)($item['quantity'] ?? 0);

        if ($inventory_id <= 0) {
            continue;
        }

        if ($quantity <= 0) {
            $quantity = 1;
        }

        $inventory = inventory_find($pdo, $inventory_id);
        if (!$inventory || $inventory['status'] !== 'Ativo') {
            $errors[] = 'Item de estoque nao encontrado.';
            continue;
        }

        if ($quantity > (int)$inventory['quantity']) {
            $errors[] = 'Quantidade insuficiente para ' . $inventory['name'] . '.';
            continue;
        }

        $unit_cost = (float)$inventory['cost'];
        $parsed[] = [
            'inventory_id' => $inventory_id,
            'quantity' => $quantity,
            'unit_cost' => $unit_cost,
            'total_cost' => $unit_cost * $quantity,
        ];
    }

    return $parsed;
}

function schedule_items_from_quote(array $quote_items): array
{
    $items = [];
    foreach ($quote_items as $item) {
        if (($item['item_type'] ?? '') !== 'produto') {
            continue;
        }
        $inventory_id = (int)($item['inventory_id'] ?? 0);
        if ($inventory_id <= 0) {
            continue;
        }
        $quantity = (int)($item['quantity'] ?? 1);
        if ($quantity <= 0) {
            $quantity = 1;
        }
        $items[] = [
            'inventory_id' => $inventory_id,
            'quantity' => $quantity,
        ];
    }
    return $items;
}

function schedule_normalize_time(string $time): string
{
    if (preg_match('/^\\d{2}:\\d{2}$/', $time)) {
        return $time . ':00';
    }
    return $time;
}

function schedule_apply_status(PDO $pdo, array $schedule, string $status): array
{
    $valid = ['Agendado', 'Confirmado', 'Concluido', 'Cancelado'];
    if (!in_array($status, $valid, true)) {
        return ['ok' => false, 'message' => 'Status invalido.'];
    }

    $pdo->beginTransaction();
    try {
        if ($status === 'Concluido' && (int)$schedule['inventory_applied'] === 0) {
            $items = schedule_items($pdo, (int)$schedule['id']);
            foreach ($items as $item) {
                $inventory = inventory_find($pdo, (int)$item['inventory_id']);
                if (!$inventory || (int)$inventory['quantity'] < (int)$item['quantity']) {
                    $pdo->rollBack();
                    return ['ok' => false, 'message' => 'Estoque insuficiente para concluir.'];
                }
            }
            foreach ($items as $item) {
                inventory_adjust_quantity($pdo, (int)$item['inventory_id'], -(int)$item['quantity']);
            }
            schedule_set_inventory_applied($pdo, (int)$schedule['id'], 1);
        }

        if ($status !== 'Concluido' && (int)$schedule['inventory_applied'] === 1) {
            $items = schedule_items($pdo, (int)$schedule['id']);
            foreach ($items as $item) {
                inventory_adjust_quantity($pdo, (int)$item['inventory_id'], (int)$item['quantity']);
            }
            schedule_set_inventory_applied($pdo, (int)$schedule['id'], 0);
        }

        schedule_update_status($pdo, (int)$schedule['id'], $status);
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['ok' => false, 'message' => 'Falha ao atualizar status.'];
    }

    if ($status === 'Confirmado') {
        $client = client_find($pdo, (int)$schedule['client_id']);
        if ($client) {
            schedules_bootstrap_whatsapp();
            WhatsAppService::notifyScheduleConfirmed($client, $schedule);
        }
    }

    if ($status === 'Cancelado') {
        $client = client_find($pdo, (int)$schedule['client_id']);
        if ($client) {
            schedules_bootstrap_whatsapp();
            WhatsAppService::notifyScheduleCancelled($client, $schedule);
        }
    }

    return ['ok' => true];
}
