<?php
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Inventory.php';
require_once __DIR__ . '/../models/Service.php';

function quotes_index(): void
{
    $quotes = quote_all(db());
    $clients = [];
    $inventory_items = [];
    $services = [];
    if (is_admin()) {
        $clients = client_all(db());
        $inventory_items = inventory_all(db());
        $services = service_all(db(), true);
    }
    $flash = flash_get();
    include __DIR__ . '/../views/quotes/index.php';
}

function quotes_create(): void
{
    $clients = client_all(db());
    $inventory_items = inventory_all(db());
    $services = service_all(db(), true);
    $flash = flash_get();
    include __DIR__ . '/../views/quotes/form.php';
}

function quotes_edit(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $quote = quote_find(db(), $id);
    if (!$quote) {
        flash_set('error', 'Orcamento nao encontrado.');
        redirect('index.php?action=quotes');
    }
    $quote_items = quote_items(db(), $id);
    $clients = client_all(db());
    $inventory_items = inventory_all(db());
    $services = service_all(db(), true);
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
        $type = $item['type'] ?? 'produto';
        $quantity = (int)($item['quantity'] ?? 0);
        $unit = (float)($item['unit_price'] ?? 0);
        $description = trim($item['description'] ?? '');

        if ($quantity <= 0) {
            continue;
        }

        if ($type === 'servico') {
            $service_id = (int)($item['service_id'] ?? 0);
            $service = $service_id > 0 ? service_find($pdo, $service_id) : null;
            if ($service && $service['status'] === 'Inativo') {
                continue;
            }
            if ($description === '' && $service) {
                $description = $service['name'];
            }
            if ($description === '' || $unit <= 0) {
                continue;
            }
            $line_total = $unit * $quantity;
            $total += $line_total;
            $parsed_items[] = [
                'inventory_id' => null,
                'service_id' => $service_id > 0 ? $service_id : null,
                'item_type' => 'servico',
                'description' => $description,
                'quantity' => $quantity,
                'unit_price' => $unit,
                'total_price' => $line_total,
            ];
            continue;
        }

        $inventory_id = (int)($item['inventory_id'] ?? 0);
        if ($inventory_id <= 0) {
            continue;
        }
        $product = inventory_find($pdo, $inventory_id);
        if (!$product || ($product['status'] ?? '') === 'Inativo') {
            continue;
        }
        $unit = (float)$product['price'];
        $line_total = $unit * $quantity;
        $total += $line_total;
        $parsed_items[] = [
            'inventory_id' => $inventory_id,
            'service_id' => null,
            'item_type' => 'produto',
            'description' => null,
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

function quotes_update(): void
{
    $pdo = db();
    $id = (int)($_POST['id'] ?? 0);
    $client_id = (int)($_POST['client_id'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');
    $items = $_POST['items'] ?? [];

    $existing = quote_find($pdo, $id);
    if (!$existing) {
        flash_set('error', 'Orcamento nao encontrado.');
        redirect('index.php?action=quotes');
    }

    if ($client_id <= 0 || empty($items)) {
        flash_set('error', 'Selecione o cliente e ao menos um item.');
        redirect('index.php?action=quotes_edit&id=' . $id);
    }

    $total = 0.0;
    $parsed_items = [];

    foreach ($items as $item) {
        $type = $item['type'] ?? 'produto';
        $quantity = (int)($item['quantity'] ?? 0);
        $unit = (float)($item['unit_price'] ?? 0);
        $description = trim($item['description'] ?? '');

        if ($quantity <= 0) {
            continue;
        }

        if ($type === 'servico') {
            $service_id = (int)($item['service_id'] ?? 0);
            $service = $service_id > 0 ? service_find($pdo, $service_id) : null;
            if ($service && $service['status'] === 'Inativo') {
                continue;
            }
            if ($description === '' && $service) {
                $description = $service['name'];
            }
            if ($description === '' || $unit <= 0) {
                continue;
            }
            $line_total = $unit * $quantity;
            $total += $line_total;
            $parsed_items[] = [
                'inventory_id' => null,
                'service_id' => $service_id > 0 ? $service_id : null,
                'item_type' => 'servico',
                'description' => $description,
                'quantity' => $quantity,
                'unit_price' => $unit,
                'total_price' => $line_total,
            ];
            continue;
        }

        $inventory_id = (int)($item['inventory_id'] ?? 0);
        if ($inventory_id <= 0) {
            continue;
        }
        $product = inventory_find($pdo, $inventory_id);
        if (!$product || ($product['status'] ?? '') === 'Inativo') {
            continue;
        }
        $unit = (float)$product['price'];
        $line_total = $unit * $quantity;
        $total += $line_total;
        $parsed_items[] = [
            'inventory_id' => $inventory_id,
            'service_id' => null,
            'item_type' => 'produto',
            'description' => null,
            'quantity' => $quantity,
            'unit_price' => $unit,
            'total_price' => $line_total,
        ];
    }

    if (empty($parsed_items)) {
        flash_set('error', 'Itens invalidos.');
        redirect('index.php?action=quotes_edit&id=' . $id);
    }

    quote_update($pdo, $id, [
        'client_id' => $client_id,
        'notes' => $notes,
        'total' => $total,
    ]);

    quote_clear_items($pdo, $id);
    foreach ($parsed_items as $item) {
        quote_add_item($pdo, $id, $item);
    }

    flash_set('success', 'Orcamento atualizado.');
    redirect('index.php?action=quotes_view&id=' . $id);
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

function quotes_delete(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $quote = quote_find(db(), $id);
    if (!$quote) {
        flash_set('error', 'Orcamento nao encontrado.');
        redirect('index.php?action=quotes');
    }
    quote_delete(db(), $id);
    flash_set('success', 'Orcamento excluido.');
    redirect('index.php?action=quotes');
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
