<?php $company = $config['company']; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .logo { width: 180px; height: auto; }
        .box { border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px; }
        .title { font-size: 20px; margin: 0 0 4px; }
        .muted { color: #64748b; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 12px; text-align: left; }
        th { background: #f8fafc; }
        .total { text-align: right; font-size: 14px; font-weight: bold; margin-top: 12px; }
        .status { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; background: #f1f5f9; }
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
        <div style="text-align:right;">
            <p class="title">Orcamento #<?php echo $quote['id']; ?></p>
            <p class="muted">Data: <?php echo date('d/m/Y', strtotime($quote['created_at'])); ?></p>
            <p class="muted">Status: <span class="status"><?php echo htmlspecialchars($quote['status']); ?></span></p>
        </div>
    </div>

    <div class="box">
        <p style="margin:0 0 4px;"><strong><?php echo htmlspecialchars($company['name']); ?></strong></p>
        <p class="muted" style="margin:0;"><?php echo htmlspecialchars($company['document']); ?></p>
        <p class="muted" style="margin:0;"><?php echo htmlspecialchars($company['phone']); ?> - <?php echo htmlspecialchars($company['email']); ?></p>
        <p class="muted" style="margin:0;"><?php echo htmlspecialchars($company['address']); ?></p>
    </div>

    <div class="box" style="margin-top:16px;">
        <p style="margin:0 0 4px;"><strong>Cliente</strong></p>
        <p class="muted" style="margin:0;"><?php echo htmlspecialchars($quote['client_name']); ?></p>
        <p class="muted" style="margin:0;"><?php echo htmlspecialchars($quote['client_company']); ?></p>
        <p class="muted" style="margin:0;"><?php echo htmlspecialchars($quote['client_email']); ?> | <?php echo htmlspecialchars($quote['client_phone']); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qtd</th>
                <th>Valor</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($item['item_name']); ?></strong><br>
                        <span class="muted"><?php echo htmlspecialchars($item['item_description']); ?></span>
                    </td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo money_br($item['unit_price']); ?></td>
                    <td><?php echo money_br($item['total_price']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">Total: <?php echo money_br($quote['total']); ?></p>

    <?php if (!empty($quote['notes'])): ?>
        <div class="box" style="margin-top:16px;">
            <strong>Observacoes</strong>
            <p class="muted" style="margin:6px 0 0;"><?php echo htmlspecialchars($quote['notes']); ?></p>
        </div>
    <?php endif; ?>
</body>
</html>
