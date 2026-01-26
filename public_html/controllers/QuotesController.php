<?php
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Product.php';

function quotes_index(): void
{
    $quotes = quote_all(db());
    $clients = [];
    $products = [];
    if (is_admin()) {
        $clients = client_all(db());
        $products = product_all(db());
    }
    $flash = flash_get();
    include __DIR__ . '/../views/quotes/index.php';
}

function quotes_create(): void
{
    $clients = client_all(db());
    $products = product_all(db());
    $flash = flash_get();
    include __DIR__ . '/../views/quotes/form.php';
}

function quotes_store(): void
{
    $pdo = db();
    $client_id = (int)($_POST['client_id'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');
    $items = $_POST['items'] ?? [];

    if ($client_id <= 0 || empty($items)) {
        flash_set('error', 'Selecione o cliente e ao menos um item.');
        redirect('index.php?action=quotes_create');
    }

    $total = 0.0;
    $parsed_items = [];

    foreach ($items as $item) {
        $product_id = (int)($item['product_id'] ?? 0);
        $quantity = (int)($item['quantity'] ?? 0);
        if ($product_id <= 0 || $quantity <= 0) {
            continue;
        }
        $product = product_find($pdo, $product_id);
        if (!$product) {
            continue;
        }
        $unit = (float)$product['price'];
        $line_total = $unit * $quantity;
        $total += $line_total;
        $parsed_items[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'unit_price' => $unit,
            'total_price' => $line_total,
        ];
    }

    if (empty($parsed_items)) {
        flash_set('error', 'Itens invalidos.');
        redirect('index.php?action=quotes_create');
    }

    $quote_id = quote_create($pdo, [
        'client_id' => $client_id,
        'status' => 'Enviado',
        'notes' => $notes,
        'total' => $total,
    ]);

    foreach ($parsed_items as $item) {
        quote_add_item($pdo, $quote_id, $item);
    }

    flash_set('success', 'Orcamento criado.');
    redirect('index.php?action=quotes_view&id=' . $quote_id);
}

function quotes_view(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $quote = quote_find(db(), $id);
    if (!$quote) {
        flash_set('error', 'Orcamento nao encontrado.');
        redirect('index.php?action=quotes');
    }
    $items = quote_items(db(), $id);
    $flash = flash_get();
    include __DIR__ . '/../views/quotes/view.php';
}

function quotes_update_status(): void
{
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'Enviado';
    quote_update_status(db(), $id, $status);
    flash_set('success', 'Status atualizado.');
    redirect('index.php?action=quotes_view&id=' . $id);
}

function quotes_generate_pdf(): void
{
    require_once __DIR__ . '/../vendor/autoload.php';

    $id = (int)($_GET['id'] ?? 0);
    $quote = quote_find(db(), $id);
    if (!$quote) {
        flash_set('error', 'Orcamento nao encontrado.');
        redirect('index.php?action=quotes');
    }

    $items = quote_items(db(), $id);
    $config = require __DIR__ . '/../config.php';

    ob_start();
    include __DIR__ . '/../views/quotes/pdf.php';
    $html = ob_get_clean();

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $file_name = 'orcamento-' . $id . '.pdf';
    $file_path = __DIR__ . '/../pdf/' . $file_name;
    file_put_contents($file_path, $dompdf->output());

    quote_update_pdf_path(db(), $id, 'pdf/' . $file_name);

    flash_set('success', 'PDF gerado com sucesso.');
    redirect('index.php?action=quotes_view&id=' . $id);
}
