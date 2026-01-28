<?php include __DIR__ . '/../partials/header.php'; ?>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="mb-6">
    <h1 class="text-2xl font-semibold">Caixa</h1>
    <p class="text-sm text-slate-500">Resumo do estoque, serviços e futuros confirmados.</p>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Estoque</p>
        <p class="mt-3 text-3xl font-semibold text-slate-900"><?php echo $inventory_stats['total_items']; ?></p>
        <p class="mt-2 text-sm text-slate-500">Itens cadastrados</p>
        <p class="mt-3 text-lg font-semibold text-emerald-600"><?php echo money_br((float)$inventory_stats['total_value']); ?></p>
        <p class="text-xs text-slate-500">Valor em estoque</p>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Serviços</p>
        <p class="mt-3 text-3xl font-semibold text-slate-900"><?php echo $service_count; ?></p>
        <p class="mt-2 text-sm text-slate-500">Serviços ativos</p>
        <p class="mt-3 text-lg font-semibold text-emerald-600"><?php echo money_br($service_total); ?></p>
        <p class="text-xs text-slate-500">Soma dos serviços</p>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Futuros</p>
        <p class="mt-3 text-3xl font-semibold text-slate-900"><?php echo $futuros; ?></p>
        <p class="mt-2 text-sm text-slate-500">Orçamentos aprovados</p>
        <p class="mt-3 text-lg font-semibold text-emerald-600"><?php echo money_br($futuros_total); ?></p>
        <p class="text-xs text-slate-500">Total aprovado</p>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Notas fiscais</p>
        <p class="mt-3 text-3xl font-semibold text-slate-900"><?php echo $total_invoices; ?></p>
        <p class="mt-2 text-sm text-slate-500">Emitidas</p>
        <p class="mt-3 text-lg font-semibold text-emerald-600"><?php echo money_br($total_invoices_value); ?></p>
        <p class="text-xs text-slate-500">Total emitido</p>
    </div>
</div>

<div class="mt-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-semibold">Extrato de entradas e saídas</h2>
        <p class="text-sm text-slate-500">Movimentações registradas ao emitir a nota fiscal.</p>
    </div>
    <div class="flex gap-3">
        <a class="btn-outline px-4 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=cashbox_excel'); ?>">Exportar Excel</a>
        <a class="btn-primary px-4 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=cashbox_pdf'); ?>">Exportar PDF</a>
    </div>
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-3">
    <div class="card-surface rounded-xl p-4">
        <p class="text-xs uppercase text-slate-500">Entradas</p>
        <p class="mt-2 text-2xl font-semibold text-emerald-600"><?php echo money_br($totals['entrada']); ?></p>
    </div>
    <div class="card-surface rounded-xl p-4">
        <p class="text-xs uppercase text-slate-500">Saídas</p>
        <p class="mt-2 text-2xl font-semibold text-rose-600"><?php echo money_br($totals['saida']); ?></p>
    </div>
    <div class="card-surface rounded-xl p-4">
        <p class="text-xs uppercase text-slate-500">Saldo</p>
        <p class="mt-2 text-2xl font-semibold text-slate-900"><?php echo money_br($saldo); ?></p>
    </div>
</div>

<div class="mt-4 card-surface rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Data</th>
                    <th class="px-4 py-3 text-left">Tipo</th>
                    <th class="px-4 py-3 text-left">Origem</th>
                    <th class="px-4 py-3 text-left">Descrição</th>
                    <th class="px-4 py-3 text-left">Qtd</th>
                    <th class="px-4 py-3 text-left">Valor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td class="px-4 py-3"><?php echo date('d/m/Y H:i', strtotime($entry['created_at'])); ?></td>
                        <td class="px-4 py-3">
                            <span class="<?php echo $entry['type'] === 'entrada' ? 'text-emerald-600' : 'text-rose-600'; ?>">
                                <?php echo ucfirst($entry['type']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($entry['origin']); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($entry['description']); ?></td>
                        <td class="px-4 py-3"><?php echo $entry['quantity'] !== null ? (int)$entry['quantity'] : '-'; ?></td>
                        <td class="px-4 py-3"><?php echo money_br((float)$entry['amount']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($entries)): ?>
                    <tr>
                        <td class="px-4 py-6 text-center text-slate-500" colspan="6">Sem movimentações registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
