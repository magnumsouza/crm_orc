<?php
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Inventory.php';

function invoices_index(): void
{
    $type = trim($_GET['type'] ?? '');
    $invoices = invoice_all(db(), $type);
    $flash = flash_get();
    include __DIR__ . '/../views/invoices/index.php';
}

function invoices_pdf(): void
{
    require_once __DIR__ . '/../vendor/autoload.php';
    $id = (int)($_GET['id'] ?? 0);
    $invoice = invoice_find(db(), $id);
    if (!$invoice) {
        flash_set('error', 'Nota fiscal nao encontrada.');
        redirect('index.php?action=invoices');
    }
    $items = invoice_items(db(), $id);

    ob_start();
    include __DIR__ . '/../views/invoices/pdf.php';
    $html = ob_get_clean();

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $file = 'nota-' . $id . '.pdf';
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    echo $dompdf->output();
    exit;
}

function invoices_xml(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $invoice = invoice_find(db(), $id);
    if (!$invoice) {
        flash_set('error', 'Nota fiscal nao encontrada.');
        redirect('index.php?action=invoices');
    }
    $items = invoice_items(db(), $id);

    $xml = new SimpleXMLElement('<nota_fiscal/>');
    $xml->addChild('id', (string)$invoice['id']);
    $xml->addChild('tipo', $invoice['type']);
    $xml->addChild('modo', $invoice['mode']);
    $cliente = $xml->addChild('cliente');
    $cliente->addChild('nome', $invoice['client_name'] ?: ($invoice['client_name_ref'] ?? ''));
    $cliente->addChild('documento', $invoice['client_document']);
    $cliente->addChild('email', $invoice['client_email']);
    $cliente->addChild('telefone', $invoice['client_phone']);
    $cliente->addChild('endereco', $invoice['client_address']);

    $itens = $xml->addChild('itens');
    foreach ($items as $item) {
        $itemXml = $itens->addChild('item');
        $itemXml->addChild('descricao', $item['description']);
        $itemXml->addChild('quantidade', (string)$item['quantity']);
        $itemXml->addChild('valor_unitario', number_format((float)$item['unit_price'], 2, '.', ''));
        $itemXml->addChild('valor_total', number_format((float)$item['total_price'], 2, '.', ''));
    }
    $xml->addChild('total', number_format((float)$invoice['total'], 2, '.', ''));

    $file = 'nota-' . $id . '.xml';
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    echo $xml->asXML();
    exit;
}

function invoices_store(): void
{
    $pdo = db();
    $schedule_id = (int)($_POST['schedule_id'] ?? 0);
    $invoice_type = trim($_POST['invoice_type'] ?? '');
    $invoice_mode = trim($_POST['invoice_mode'] ?? 'cadastro');
    $invoice_items = $_POST['invoice_items'] ?? [];
    $invoice_client_name = trim($_POST['invoice_client_name'] ?? '');
    $invoice_client_document = trim($_POST['invoice_client_document'] ?? '');
    $invoice_client_email = trim($_POST['invoice_client_email'] ?? '');
    $invoice_client_phone = trim($_POST['invoice_client_phone'] ?? '');
    $invoice_client_address = trim($_POST['invoice_client_address'] ?? '');

    if ($schedule_id <= 0 || $invoice_type === '') {
        flash_set('error', 'Selecione o tipo de nota fiscal.');
        redirect('index.php?action=schedules');
    }

    $schedule = schedule_find($pdo, $schedule_id);
    if (!$schedule) {
        flash_set('error', 'Agendamento nao encontrado.');
        redirect('index.php?action=schedules');
    }

    if ($schedule['status'] !== 'Concluido') {
        flash_set('error', 'A nota fiscal so pode ser emitida para agendamentos concluidos.');
        redirect('index.php?action=schedules');
    }

    $existing = $pdo->prepare('SELECT id FROM invoices WHERE schedule_id = ? LIMIT 1');
    $existing->execute([$schedule_id]);
    if ($existing->fetch()) {
        flash_set('error', 'Nota fiscal ja emitida para este agendamento.');
        redirect('index.php?action=schedules');
    }

    $invoice_total = 0.0;
    $client_name = $invoice_client_name;
    $client_email = $invoice_client_email;
    $client_phone = $invoice_client_phone;
    $client_document = $invoice_client_document;
    $client_address = $invoice_client_address;
    $client_ref_id = null;

    if ($invoice_mode === 'cadastro') {
        $client = client_find($pdo, (int)$schedule['client_id']);
        if ($client) {
            $client_ref_id = (int)$client['id'];
            $client_name = $client['name'];
            $client_email = $client['email'];
            $client_phone = $client['phone'];
        }
    }

    $invoice_id = invoice_create($pdo, [
        'schedule_id' => $schedule_id,
        'client_id' => $client_ref_id,
        'type' => $invoice_type,
        'mode' => $invoice_mode === 'avulsa' ? 'avulsa' : 'cadastro',
        'client_name' => $client_name,
        'client_document' => $client_document,
        'client_email' => $client_email,
        'client_phone' => $client_phone,
        'client_address' => $client_address,
        'total' => 0,
    ]);

    if ($invoice_mode === 'cadastro') {
        $items = schedule_items($pdo, $schedule_id);
        foreach ($items as $item) {
            $inventory = inventory_find($pdo, (int)$item['inventory_id']);
            if (!$inventory) {
                continue;
            }
            $unit_price = (float)$inventory['price'];
            $line_total = $unit_price * (int)$item['quantity'];
            $invoice_total += $line_total;
            invoice_add_item($pdo, $invoice_id, [
                'product_id' => (int)$inventory['id'],
                'description' => $inventory['name'],
                'quantity' => (int)$item['quantity'],
                'unit_price' => $unit_price,
                'total_price' => $line_total,
            ]);
        }

        if (empty($items)) {
            invoice_add_item($pdo, $invoice_id, [
                'product_id' => null,
                'description' => $schedule['service_description'],
                'quantity' => 1,
                'unit_price' => 0,
                'total_price' => 0,
            ]);
        }
    } else {
        foreach ($invoice_items as $item) {
            $desc = trim($item['description'] ?? '');
            $qty = (int)($item['quantity'] ?? 0);
            $unit = (float)($item['unit_price'] ?? 0);
            if ($desc === '' || $qty <= 0) {
                continue;
            }
            $line_total = $unit * $qty;
            $invoice_total += $line_total;
            invoice_add_item($pdo, $invoice_id, [
                'product_id' => null,
                'description' => $desc,
                'quantity' => $qty,
                'unit_price' => $unit,
                'total_price' => $line_total,
            ]);
        }
    }

    $pdo->prepare('UPDATE invoices SET total = ? WHERE id = ?')->execute([$invoice_total, $invoice_id]);

    flash_set('success', 'Nota fiscal emitida.');
    redirect('index.php?action=invoices');
}
