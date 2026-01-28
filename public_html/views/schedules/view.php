<?php include __DIR__ . '/../partials/header.php'; ?>
<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold">Detalhes do agendamento</h2>
        <p class="text-sm text-slate-500">Cliente, servico e itens utilizados.</p>
    </div>
    <div class="flex items-center gap-3">
        <?php if (is_admin()): ?>
            <a class="btn-outline px-3 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=schedules_edit&id=' . $schedule['id']); ?>" data-confirm-link data-confirm-title="Editar agendamento" data-confirm-message="Deseja editar este agendamento?" data-confirm-text="Editar">Editar</a>
            <a class="btn-danger px-3 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=schedules_delete&id=' . $schedule['id']); ?>" data-confirm-link data-confirm-title="Cancelar agendamento" data-confirm-message="Tem certeza que deseja cancelar este agendamento?" data-confirm-text="Cancelar" data-confirm-variant="danger">Cancelar</a>
        <?php endif; ?>
        <a class="btn-outline px-3 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=schedules'); ?>">Voltar</a>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <div class="card-surface rounded-2xl p-5 lg:col-span-2">
        <div class="space-y-4">
            <div>
                <p class="text-xs uppercase text-slate-500">Cliente</p>
                <p class="mt-1 text-lg font-semibold text-slate-800"><?php echo htmlspecialchars($schedule['client_name']); ?></p>
                <?php if (!empty($schedule['client_phone'])): ?>
                    <a class="text-sm text-brand-700 hover:text-brand-900" href="https://wa.me/<?php echo phone_to_whatsapp($schedule['client_phone']); ?>" target="_blank">Abrir WhatsApp</a>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-xs uppercase text-slate-500">Servico</p>
                <p class="mt-1 text-slate-700"><?php echo nl2br(htmlspecialchars($schedule['service_description'])); ?></p>
            </div>
            <?php if (!empty($schedule['notes'])): ?>
                <div>
                    <p class="text-xs uppercase text-slate-500">Observacoes</p>
                    <p class="mt-1 text-slate-700"><?php echo nl2br(htmlspecialchars($schedule['notes'])); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-surface rounded-2xl p-5">
        <p class="text-xs uppercase text-slate-500">Agendamento</p>
        <p class="mt-2 text-lg font-semibold text-slate-800"><?php echo date('d/m/Y', strtotime($schedule['scheduled_date'])); ?></p>
        <p class="text-sm text-slate-600"><?php echo substr($schedule['scheduled_time'], 0, 5); ?></p>
        <div class="mt-4">
            <p class="text-xs uppercase text-slate-500">Status</p>
            <span class="mt-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700"><?php echo $schedule['status']; ?></span>
        </div>
    </div>
</div>

<div class="mt-6 card-surface rounded-2xl p-5">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold text-slate-800">Itens do servico</h3>
    </div>
    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-slate-500">
                <tr>
                    <th class="py-2">Item</th>
                    <th class="py-2">SKU</th>
                    <th class="py-2">Quantidade</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="py-3 font-medium text-slate-800"><?php echo htmlspecialchars($item['inventory_name']); ?></td>
                        <td class="py-3 text-slate-500"><?php echo htmlspecialchars($item['inventory_sku']); ?></td>
                        <td class="py-3 text-slate-700"><?php echo (int)$item['quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="3" class="py-6 text-center text-slate-500">Nenhum item informado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
