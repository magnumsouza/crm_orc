<?php include __DIR__ . '/../partials/header.php'; ?>
<?php $is_edit = !empty($schedule); ?>
<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold"><?php echo $is_edit ? 'Editar agendamento' : 'Novo agendamento'; ?></h2>
        <p class="text-sm text-slate-500">Horario comercial: <?php echo substr($settings['business_hours_start'], 0, 5); ?> - <?php echo substr($settings['business_hours_end'], 0, 5); ?> (seg-sab).</p>
    </div>
    <a class="text-sm text-slate-600 hover:text-slate-900" href="<?php echo base_url('index.php?action=schedules'); ?>">Voltar</a>
</div>

<form class="mt-6 space-y-6" method="post" action="<?php echo base_url('index.php?action=' . ($is_edit ? 'schedules_update' : 'schedules_store')); ?>" data-slots-url="<?php echo base_url('index.php?action=schedules_api_slots'); ?>">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?php echo $schedule['id']; ?>">
    <?php endif; ?>

    <div class="grid gap-6 lg:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-700">Cliente</label>
            <select class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="client_id" required>
                <option value="">Selecione</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id']; ?>" <?php echo $is_edit && (int)$schedule['client_id'] === (int)$client['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($client['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Servico</label>
            <textarea class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="service_description" rows="3" required><?php echo $is_edit ? htmlspecialchars($schedule['service_description']) : ''; ?></textarea>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-700">Data</label>
            <input class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="date" name="scheduled_date" data-schedule-date value="<?php echo $is_edit ? $schedule['scheduled_date'] : ''; ?>" required>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Horario</label>
            <select class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="scheduled_time" data-schedule-time data-current-time="<?php echo $is_edit ? substr($schedule['scheduled_time'], 0, 5) : ''; ?>" required>
                <option value="">Selecione uma data</option>
            </select>
            <p class="mt-2 text-xs text-slate-500" data-slots-message>Selecione uma data para ver os horarios livres.</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">Itens de estoque</h3>
                <p class="text-xs text-slate-500">Adicione produtos/pecas usados no servico.</p>
            </div>
            <button class="rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700 hover:bg-slate-50" type="button" data-add-item>Adicionar item</button>
        </div>

        <div class="mt-4 space-y-3" data-items-container>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <div class="grid gap-3 md:grid-cols-[1fr_120px_40px] items-end" data-item-row>
                        <div>
                            <label class="text-xs font-medium text-slate-600">Item</label>
                            <select class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="items[][inventory_id]" data-item-select required>
                                <option value="">Selecione</option>
                                <?php foreach ($inventory as $stock): ?>
                                    <option value="<?php echo $stock['id']; ?>" data-available="<?php echo $stock['quantity']; ?>" <?php echo (int)$item['inventory_id'] === (int)$stock['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($stock['name']); ?> (<?php echo $stock['quantity']; ?> disponivel)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="mt-1 text-xs text-slate-500" data-stock-label></p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-600">Quantidade</label>
                            <input class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="number" min="1" name="items[][quantity]" value="<?php echo (int)$item['quantity']; ?>" required>
                        </div>
                        <button class="text-xs text-rose-600 hover:text-rose-700" type="button" data-remove-item>Remover</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-sm text-slate-500" data-empty-items>Nenhum item adicionado.</p>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Observacoes</label>
        <textarea class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="notes" rows="3"><?php echo $is_edit ? htmlspecialchars($schedule['notes']) : ''; ?></textarea>
    </div>

    <div class="flex items-center gap-3">
        <button class="rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700" type="submit"><?php echo $is_edit ? 'Salvar alteracoes' : 'Agendar'; ?></button>
        <a class="text-sm text-slate-600 hover:text-slate-900" href="<?php echo base_url('index.php?action=schedules'); ?>">Cancelar</a>
    </div>
</form>

<template id="scheduleItemTemplate">
    <div class="grid gap-3 md:grid-cols-[1fr_120px_40px] items-end" data-item-row>
        <div>
            <label class="text-xs font-medium text-slate-600">Item</label>
            <select class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="items[][inventory_id]" data-item-select required>
                <option value="">Selecione</option>
                <?php foreach ($inventory as $stock): ?>
                    <option value="<?php echo $stock['id']; ?>" data-available="<?php echo $stock['quantity']; ?>">
                    <?php echo htmlspecialchars($stock['name']); ?> (<?php echo $stock['quantity']; ?> disponivel)
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1 text-xs text-slate-500" data-stock-label></p>
        </div>
        <div>
            <label class="text-xs font-medium text-slate-600">Quantidade</label>
            <input class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="number" min="1" name="items[][quantity]" value="1" required>
        </div>
        <button class="text-xs text-rose-600 hover:text-rose-700" type="button" data-remove-item>Remover</button>
    </div>
</template>

<script src="<?php echo base_url('assets/js/schedules.js'); ?>"></script>
<?php include __DIR__ . '/../partials/footer.php'; ?>
