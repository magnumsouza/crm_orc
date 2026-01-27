<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Notas Fiscais</h1>
        <p class="text-sm text-slate-500">NFe e NFS-e emitidas.</p>
    </div>
    <form class="flex items-center gap-3" method="get" action="<?php echo base_url('index.php'); ?>">
        <input type="hidden" name="action" value="invoices">
        <select name="type" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <option value="">Todas</option>
            <option value="NFE" <?php echo ($type ?? '') === 'NFE' ? 'selected' : ''; ?>>NFe</option>
            <option value="NFSE" <?php echo ($type ?? '') === 'NFSE' ? 'selected' : ''; ?>>NFS-e</option>
        </select>
        <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm">Filtrar</button>
    </form>
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
                <th class="text-left px-4 py-3">Tipo</th>
                <th class="text-left px-4 py-3">Cliente</th>
                <th class="text-left px-4 py-3">Total</th>
                <th class="text-left px-4 py-3">Data</th>
                <th class="text-left px-4 py-3">Acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $invoice): ?>
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">#<?php echo $invoice['id']; ?></td>
                    <td class="px-4 py-3"><?php echo $invoice['type']; ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($invoice['client_name'] ?: ($invoice['client_name_ref'] ?? '')); ?></td>
                    <td class="px-4 py-3"><?php echo money_br((float)$invoice['total']); ?></td>
                    <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($invoice['created_at'])); ?></td>
                    <td class="px-4 py-3">
                        <div class="flex flex-nowrap gap-3">
                            <a href="<?php echo base_url('index.php?action=invoices_pdf&id=' . $invoice['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-amber-50 p-1.5 text-amber-700 hover:bg-amber-100 md:p-2" aria-label="PDF" title="PDF">
                                <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <path d="M14 2v6h6"></path>
                                </svg>
                            </a>
                            <a href="<?php echo base_url('index.php?action=invoices_xml&id=' . $invoice['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-blue-50 p-1.5 text-blue-700 hover:bg-blue-100 md:p-2" aria-label="XML" title="XML">
                                <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16v16H4z"></path>
                                    <path d="M8 9l-3 3 3 3"></path>
                                    <path d="M16 9l3 3-3 3"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($invoices)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">Nenhuma nota fiscal encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
