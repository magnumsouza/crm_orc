<?php
require_once __DIR__ . '/../models/Invoice.php';

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
