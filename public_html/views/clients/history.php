<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Historico - <?php echo htmlspecialchars($client['name']); ?></h1>
    <p class="text-sm text-slate-500">Todos os orcamentos deste cliente.</p>
</div>

<div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="text-left px-4 py-3">ID</th>
                <th class="text-left px-4 py-3">Data</th>
                <th class="text-left px-4 py-3">Total</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quotes as $quote): ?>
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">#<?php echo $quote['id']; ?></td>
                    <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($quote['created_at'])); ?></td>
                    <td class="px-4 py-3"><?php echo money_br($quote['total']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($quote['status']); ?></td>
                    <td class="px-4 py-3">
                        <a class="text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=quotes_view&id=' . $quote['id']); ?>">Ver</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($quotes)): ?>
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Nenhum orcamento encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-6">
    <a class="rounded-lg border border-slate-200 px-4 py-2" href="<?php echo base_url('index.php?action=clients'); ?>">Voltar</a>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
