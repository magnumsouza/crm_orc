<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Orcamentos</h1>
        <p class="text-sm text-slate-500">Acompanhe propostas enviadas e aprovadas.</p>
    </div>
    <a class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700" href="<?php echo base_url('index.php?action=quotes_create'); ?>">Novo Orcamento</a>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="text-left px-4 py-3">ID</th>
                <th class="text-left px-4 py-3">Cliente</th>
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
                    <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($quote['client_name']); ?></td>
                    <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($quote['created_at'])); ?></td>
                    <td class="px-4 py-3"><?php echo money_br($quote['total']); ?></td>
                    <td class="px-4 py-3">
                        <?php if ($quote['status'] === 'Aprovado'): ?>
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">● Aprovado</span>
                        <?php elseif ($quote['status'] === 'Recusado'): ?>
                            <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-red-700">● Recusado</span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-amber-700">● Enviado</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <a class="text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=quotes_view&id=' . $quote['id']); ?>">Ver</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($quotes)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">Nenhum orcamento encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
