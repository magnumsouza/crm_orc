<?php $company = $config['company']; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; }
        .title { font-size: 20px; margin: 0 0 8px; }
        .muted { color: #64748b; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; text-align: left; }
        th { background: #f8fafc; }
        .totals { margin-top: 12px; font-size: 12px; }
    </style>
</head>
<body>
    <h1 class="title">Extrato de Caixa</h1>
    <p class="muted"><?php echo htmlspecialchars($company['name']); ?> • <?php echo date('d/m/Y H:i'); ?></p>

    <div class="totals">
        <strong>Entradas:</strong> <?php echo money_br($totals['entrada']); ?> |
        <strong>Saídas:</strong> <?php echo money_br($totals['saida']); ?> |
        <strong>Saldo:</strong> <?php echo money_br($saldo); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Origem</th>
                <th>Descrição</th>
                <th>Qtd</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entries as $entry): ?>
                <tr>
                    <td><?php echo date('d/m/Y H:i', strtotime($entry['created_at'])); ?></td>
                    <td><?php echo ucfirst($entry['type']); ?></td>
                    <td><?php echo htmlspecialchars($entry['origin']); ?></td>
                    <td><?php echo htmlspecialchars($entry['description']); ?></td>
                    <td><?php echo $entry['quantity'] !== null ? (int)$entry['quantity'] : '-'; ?></td>
                    <td><?php echo money_br((float)$entry['amount']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
