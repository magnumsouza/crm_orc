<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nota Fiscal #<?php echo $invoice['id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .logo { width: 180px; height: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        .meta { margin-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <svg width="180" height="56" viewBox="0 0 320 100" xmlns="http://www.w3.org/2000/svg">
              <g transform="translate(10,10)">
                <rect x="0" y="20" width="50" height="40" rx="6" fill="#1E88E5"/>
                <circle cx="25" cy="40" r="10" fill="#43A047"/>
                <g stroke="#43A047" stroke-width="3">
                  <line x1="25" y1="20" x2="25" y2="10"/>
                  <line x1="25" y1="60" x2="25" y2="70"/>
                  <line x1="5" y1="40" x2="-5" y2="40"/>
                  <line x1="45" y1="40" x2="55" y2="40"/>
                </g>
              </g>
              <text x="80" y="55" font-family="Segoe UI, Arial, sans-serif" font-size="28" fill="#1E88E5" font-weight="600">WebService</text>
              <text x="82" y="78" font-family="Segoe UI, Arial, sans-serif" font-size="14" fill="#555">Orçamentos • Estoque • Serviços</text>
            </svg>
        </div>
        <h1>Nota Fiscal <?php echo $invoice['type']; ?> #<?php echo $invoice['id']; ?></h1>
    </div>
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
