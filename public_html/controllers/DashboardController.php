<?php
require_once __DIR__ . '/../models/Quote.php';

function show_dashboard(): void
{
    $counts = quote_counts(db());
    $flash = flash_get();
    include __DIR__ . '/../views/dashboard.php';
}
