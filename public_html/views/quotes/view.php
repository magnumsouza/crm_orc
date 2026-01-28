<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Orçamento #<?php echo $quote['id']; ?></h1>
        <p class="text-sm text-slate-500">Cliente: <?php echo htmlspecialchars($quote['client_name']); ?></p>
    </div>
    <a class="btn-outline px-4 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=quotes'); ?>">Voltar</a>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 card-surface rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Itens</h2>
            <?php if (!empty($quote['pdf_path'])): ?>
                <a class="text-brand-700 hover:text-brand-900" href="<?php echo base_url($quote['pdf_path']); ?>" target="_blank">Abrir PDF</a>
            <?php endif; ?>
        </div>
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="text-left px-4 py-3">Item</th>
                        <th class="text-left px-4 py-3">Qtd</th>
                        <th class="text-left px-4 py-3">Valor</th>
                        <th class="text-left px-4 py-3">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $is_service = ($item['item_type'] ?? 'produto') === 'servico';
                        $item_name = $is_service ? ($item['service_name'] ?: $item['description']) : $item['product_name'];
                        ?>
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-medium">
                                <?php echo htmlspecialchars($item_name); ?>
                                <?php if ($is_service): ?>
                                    <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-600">Servico</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><?php echo $item['quantity']; ?></td>
                            <td class="px-4 py-3"><?php echo money_br($item['unit_price']); ?></td>
                            <td class="px-4 py-3"><?php echo money_br($item['total_price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right text-lg font-semibold">Total: <?php echo money_br($quote['total']); ?></div>
        <?php if (!empty($quote['notes'])): ?>
            <div class="mt-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-600">
                <strong>Observacoes:</strong> <?php echo htmlspecialchars($quote['notes']); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-surface rounded-2xl p-6 space-y-4">
        <div>
            <p class="text-xs uppercase text-slate-500">Status atual</p>
            <p class="mt-2 text-lg font-semibold"><?php echo htmlspecialchars($quote['status']); ?></p>
        </div>
        <?php if (is_admin()): ?>
            <form method="post" action="<?php echo base_url('index.php?action=quotes_update_status'); ?>" class="space-y-3">
                <input type="hidden" name="id" value="<?php echo $quote['id']; ?>">
                <select name="status" class="w-full rounded-lg border border-slate-200 px-4 py-2">
                    <option value="Enviado" <?php echo $quote['status'] === 'Enviado' ? 'selected' : ''; ?>>Enviado</option>
                    <option value="Aprovado" <?php echo $quote['status'] === 'Aprovado' ? 'selected' : ''; ?>>Aprovado</option>
                    <option value="Recusado" <?php echo $quote['status'] === 'Recusado' ? 'selected' : ''; ?>>Recusado</option>
                </select>
                <button class="btn-outline w-full px-4 py-2 text-sm font-semibold transition">Atualizar</button>
            </form>
        <?php endif; ?>

        <a class="btn-primary w-full inline-flex justify-center px-4 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=quotes_pdf&id=' . $quote['id']); ?>">Gerar PDF</a>

        <?php if (!empty($quote['pdf_path'])): ?>
            <?php $whats = phone_to_whatsapp($quote['client_phone']); ?>
            <?php $pdf_link = base_url($quote['pdf_path']); ?>
            <?php if ($whats): ?>
                <a class="btn-success w-full inline-flex justify-center px-4 py-2 text-sm font-semibold transition" href="https://wa.me/<?php echo $whats; ?>?text=<?php echo urlencode('Segue o orcamento: ' . $pdf_link); ?>" target="_blank">Enviar por WhatsApp</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
