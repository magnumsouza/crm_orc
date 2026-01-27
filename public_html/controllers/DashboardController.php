<?php
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Inventory.php';
require_once __DIR__ . '/../models/Schedule.php';

function show_dashboard(): void
{
    $counts = quote_counts(db());
    $schedule_counts = schedule_counts(db());
    $inventory_stats = inventory_stats(db());
    $client_count = client_count(db());
    $clients = [];
    $inventory_items = [];
    if (is_admin()) {
        $clients = client_all(db());
        $inventory_items = inventory_all(db());
    }
    $flash = flash_get();
    include __DIR__ . '/../views/dashboard.php';
}
