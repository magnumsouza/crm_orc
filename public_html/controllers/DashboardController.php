<?php
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Inventory.php';
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/Invoice.php';

function show_dashboard(): void
{
    $counts = quote_counts(db());
    $schedule_counts = schedule_counts(db());
    $inventory_stats = inventory_stats(db());
    $client_count = client_count(db());
    $services = service_all(db(), true);
    $service_count = count($services);
    $service_total = 0.0;
    foreach ($services as $service) {
        $service_total += (float)$service['price'];
    }

    $stmt = db()->prepare("SELECT COUNT(*) AS total, COALESCE(SUM(total),0) AS total_value FROM quotes WHERE status = 'Aprovado'");
    $stmt->execute();
    $futuros_row = $stmt->fetch();
    $futuros = (int)($futuros_row['total'] ?? 0);
    $futuros_total = (float)($futuros_row['total_value'] ?? 0);

    $stmt = db()->prepare('SELECT COUNT(*) AS total, COALESCE(SUM(total),0) AS total_value FROM invoices');
    $stmt->execute();
    $invoice_row = $stmt->fetch();
    $total_invoices = (int)($invoice_row['total'] ?? 0);
    $total_invoices_value = (float)($invoice_row['total_value'] ?? 0);
    $clients = [];
    $inventory_items = [];
    $services = [];
    if (is_admin()) {
        $clients = client_all(db());
        $inventory_items = inventory_all(db());
        $services = service_all(db(), true);
    }
    $flash = flash_get();
    include __DIR__ . '/../views/dashboard.php';
}
