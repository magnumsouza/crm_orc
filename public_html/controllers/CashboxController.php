<?php
require_once __DIR__ . '/../models/Inventory.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Cashbox.php';

function cashbox_index(): void
{
    $inventory_stats = inventory_stats(db());
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

    $entries = cashbox_all(db());
    $totals = cashbox_totals(db());
    $saldo = $totals['entrada'] - $totals['saida'];

    $flash = flash_get();
    include __DIR__ . '/../views/cashbox/index.php';
}

function cashbox_pdf(): void
{
    require_once __DIR__ . '/../vendor/autoload.php';
    $entries = cashbox_all(db(), 500);
    $totals = cashbox_totals(db());
    $saldo = $totals['entrada'] - $totals['saida'];
    $config = require __DIR__ . '/../config.php';

    ob_start();
    include __DIR__ . '/../views/cashbox/pdf.php';
    $html = ob_get_clean();

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $file = 'caixa-' . date('Y-m-d') . '.pdf';
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    echo $dompdf->output();
    exit;
}

function cashbox_excel(): void
{
    $entries = cashbox_all(db(), 1000);
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename="caixa-' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Data', 'Tipo', 'Origem', 'Descricao', 'Quantidade', 'Valor']);
    foreach ($entries as $entry) {
        fputcsv($output, [
            date('d/m/Y H:i', strtotime($entry['created_at'])),
            $entry['type'],
            $entry['origin'],
            $entry['description'],
            $entry['quantity'],
            number_format((float)$entry['amount'], 2, ',', '.'),
        ]);
    }
    fclose($output);
    exit;
}
