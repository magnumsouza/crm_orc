<?php $company = $config['company']; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .logo { width: 80px; height: 80px; background: #4d7dff; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 12px; }
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
        <div>
            <div class="logo">C</div>
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
