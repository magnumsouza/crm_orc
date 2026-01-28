<?php

function schedule_counts(PDO $pdo): array
{
    $total = $pdo->query("SELECT COUNT(*) AS total FROM schedules")->fetch()['total'] ?? 0;
    $agendados = $pdo->query("SELECT COUNT(*) AS total FROM schedules WHERE status = 'Agendado'")->fetch()['total'] ?? 0;
    $confirmados = $pdo->query("SELECT COUNT(*) AS total FROM schedules WHERE status = 'Confirmado'")->fetch()['total'] ?? 0;
    $concluidos = $pdo->query("SELECT COUNT(*) AS total FROM schedules WHERE status = 'Concluido'")->fetch()['total'] ?? 0;
    $cancelados = $pdo->query("SELECT COUNT(*) AS total FROM schedules WHERE status = 'Cancelado'")->fetch()['total'] ?? 0;

    return [
        'total' => (int)$total,
        'agendados' => (int)$agendados,
        'confirmados' => (int)$confirmados,
        'concluidos' => (int)$concluidos,
        'cancelados' => (int)$cancelados,
    ];
}

function schedule_all(PDO $pdo, string $search = ''): array
{
    $query = "
        SELECT s.*, c.name AS client_name, c.phone AS client_phone
        FROM schedules s
        JOIN clients c ON c.id = s.client_id
    ";

    if ($search !== '') {
        $query .= " WHERE c.name LIKE ? OR s.service_description LIKE ? ";
        $query .= " ORDER BY s.scheduled_date DESC, s.scheduled_time DESC";
        $term = '%' . $search . '%';
        $stmt = $pdo->prepare($query);
        $stmt->execute([$term, $term]);
        return $stmt->fetchAll();
    }

    $query .= " ORDER BY s.scheduled_date DESC, s.scheduled_time DESC";
    return $pdo->query($query)->fetchAll();
}

function schedule_find(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("
        SELECT s.*, c.name AS client_name, c.phone AS client_phone, c.email AS client_email, c.company AS client_company
        FROM schedules s
        JOIN clients c ON c.id = s.client_id
        WHERE s.id = ?
    ");
    $stmt->execute([$id]);
    $schedule = $stmt->fetch();
    return $schedule ?: null;
}

function schedule_items(PDO $pdo, int $schedule_id): array
{
    $stmt = $pdo->prepare("
        SELECT si.*, i.name AS inventory_name, i.sku AS inventory_sku, i.quantity AS inventory_quantity
        FROM schedule_items si
        JOIN inventory i ON i.id = si.inventory_id
        WHERE si.schedule_id = ?
        ORDER BY i.name ASC
    ");
    $stmt->execute([$schedule_id]);
    return $stmt->fetchAll();
}

function schedule_create(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare("
        INSERT INTO schedules (client_id, quote_id, service_description, scheduled_date, scheduled_time, status, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['client_id'],
        $data['quote_id'],
        $data['service_description'],
        $data['scheduled_date'],
        $data['scheduled_time'],
        $data['status'],
        $data['notes'],
    ]);
    return (int)$pdo->lastInsertId();
}

function schedule_add_item(PDO $pdo, int $schedule_id, array $item): void
{
    $stmt = $pdo->prepare("
        INSERT INTO schedule_items (schedule_id, inventory_id, quantity, unit_cost, total_cost)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $schedule_id,
        $item['inventory_id'],
        $item['quantity'],
        $item['unit_cost'],
        $item['total_cost'],
    ]);
}

function schedule_replace_items(PDO $pdo, int $schedule_id, array $items): void
{
    $stmt = $pdo->prepare("DELETE FROM schedule_items WHERE schedule_id = ?");
    $stmt->execute([$schedule_id]);
    foreach ($items as $item) {
        schedule_add_item($pdo, $schedule_id, $item);
    }
}

function schedule_update(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare("
        UPDATE schedules
        SET client_id = ?, quote_id = ?, service_description = ?, scheduled_date = ?, scheduled_time = ?, notes = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([
        $data['client_id'],
        $data['quote_id'],
        $data['service_description'],
        $data['scheduled_date'],
        $data['scheduled_time'],
        $data['notes'],
        $id,
    ]);
}

function schedule_update_status(PDO $pdo, int $id, string $status): void
{
    $stmt = $pdo->prepare("UPDATE schedules SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$status, $id]);
}

function schedule_set_inventory_applied(PDO $pdo, int $id, int $applied): void
{
    $stmt = $pdo->prepare("UPDATE schedules SET inventory_applied = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$applied, $id]);
}

function schedule_delete(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare("UPDATE schedules SET status = 'Cancelado', updated_at = NOW() WHERE id = ?");
    $stmt->execute([$id]);
}

function schedule_by_date(PDO $pdo, string $date): array
{
    $stmt = $pdo->prepare("SELECT * FROM schedules WHERE scheduled_date = ? AND status != 'Cancelado'");
    $stmt->execute([$date]);
    return $stmt->fetchAll();
}

function schedule_get_settings(PDO $pdo): array
{
    $settings = $pdo->query("SELECT * FROM schedule_settings WHERE id = 1")->fetch();
    if ($settings) {
        return $settings;
    }
    return [
        'business_hours_start' => '07:00:00',
        'business_hours_end' => '17:00:00',
        'business_days' => '1,2,3,4,5,6',
    ];
}

function schedule_is_valid_datetime(PDO $pdo, string $date, string $time): bool
{
    $settings = schedule_get_settings($pdo);
    $date_time = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time);
    if (!$date_time) {
        return false;
    }

    $now = new DateTime('now');
    if ($date_time <= $now) {
        return false;
    }

    $day_of_week = (int)$date_time->format('N');
    $days = array_map('intval', array_filter(explode(',', (string)$settings['business_days'])));
    if (!in_array($day_of_week, $days, true)) {
        return false;
    }

    $start = $settings['business_hours_start'];
    $end = $settings['business_hours_end'];
    $time_only = $date_time->format('H:i:s');
    if ($time_only < $start || $time_only > $end) {
        return false;
    }

    return true;
}

function schedule_is_slot_available(PDO $pdo, string $date, string $time, ?int $ignore_id = null): bool
{
    $query = "SELECT COUNT(*) AS total FROM schedules WHERE scheduled_date = ? AND scheduled_time = ? AND status != 'Cancelado'";
    $params = [$date, $time];
    if ($ignore_id) {
        $query .= " AND id != ?";
        $params[] = $ignore_id;
    }
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $count = $stmt->fetch()['total'] ?? 0;
    return (int)$count === 0;
}

function schedule_get_available_slots(PDO $pdo, string $date): array
{
    $settings = schedule_get_settings($pdo);
    $business_days = array_map('intval', array_filter(explode(',', (string)$settings['business_days'])));
    $day = DateTime::createFromFormat('Y-m-d', $date);
    if (!$day) {
        return [];
    }
    $day_of_week = (int)$day->format('N');
    if (!in_array($day_of_week, $business_days, true)) {
        return [];
    }

    $start = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' ' . $settings['business_hours_start']);
    $end = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' ' . $settings['business_hours_end']);
    if (!$start || !$end) {
        return [];
    }

    $existing = schedule_by_date($pdo, $date);
    $booked = [];
    foreach ($existing as $item) {
        $booked[] = substr($item['scheduled_time'], 0, 5);
    }

    $slots = [];
    $current = clone $start;
    while ($current <= $end) {
        $slot = $current->format('H:i');
        if (!in_array($slot, $booked, true)) {
            $slots[] = $slot;
        }
        $current->modify('+30 minutes');
    }

    return $slots;
}
