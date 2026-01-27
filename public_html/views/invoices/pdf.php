<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nota Fiscal #<?php echo $invoice['id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        .meta { margin-top: 8px; }
    </style>
</head>
<body>
    <h1>Nota Fiscal <?php echo $invoice['type']; ?> #<?php echo $invoice['id']; ?></h1>
    <div class="meta">
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($invoice['client_name'] ?: ($invoice['client_name_ref'] ?? '')); ?></p>
        <p><strong>Documento:</strong> <?php echo htmlspecialchars($invoice['client_document']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($invoice['client_email']); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($invoice['client_phone']); ?></p>
        <p><strong>Endereco:</strong> <?php echo htmlspecialchars($invoice['client_address']); ?></p>
        <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($invoice['created_at'])); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Descricao</th>
                <th>Qtd</th>
                <th>Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo (int)$item['quantity']; ?></td>
                    <td><?php echo number_format((float)$item['unit_price'], 2, ',', '.'); ?></td>
                    <td><?php echo number_format((float)$item['total_price'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Total:</strong> <?php echo number_format((float)$invoice['total'], 2, ',', '.'); ?></p>
</body>
</html>
