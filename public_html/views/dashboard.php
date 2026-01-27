<?php include __DIR__ . '/partials/header.php'; ?>
<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>

    
<?php endif; ?>

<div class="mt-8 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold">Comece um novo orçamento</h2>
        <p class="text-sm text-slate-500">Crie propostas com ítens e envie rapidamente.</p>
    </div>
    <?php if (is_admin()): ?>
        <a class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700" href="<?php echo base_url('index.php?action=quotes_create'); ?>">Novo Orçamento</a>
    
<?php endif; ?>

</div>
<br>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs uppercase text-slate-500">Orçamentos criados</p>
        <p class="mt-4 text-3xl font-semibold text-brand-700"><?php echo $counts['total']; ?></p>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs uppercase text-slate-500">Aprovados</p>
        <p class="mt-4 text-3xl font-semibold text-emerald-600"><?php echo $counts['approved']; ?></p>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs uppercase text-slate-500">Recusados</p>
        <p class="mt-4 text-3xl font-semibold text-red-500"><?php echo $counts['rejected']; ?></p>
    </div>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs uppercase text-slate-500">Agendamentos</p>
        <div class="mt-4 space-y-2 text-sm text-slate-600">
            <div class="flex items-center justify-between">
                <span>Total</span>
                <span class="font-semibold text-slate-800"><?php echo $schedule_counts['total']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Agendados</span>
                <span class="font-semibold text-blue-600"><?php echo $schedule_counts['agendados']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Confirmados</span>
                <span class="font-semibold text-amber-600"><?php echo $schedule_counts['confirmados']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Concluidos</span>
                <span class="font-semibold text-emerald-600"><?php echo $schedule_counts['concluidos']; ?></span>
            </div>
        </div>
        <a class="mt-4 inline-flex text-sm text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=schedules'); ?>">Ver agendamentos</a>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs uppercase text-slate-500">Inventario</p>
        <div class="mt-4 space-y-2 text-sm text-slate-600">
            <div class="flex items-center justify-between">
                <span>Itens ativos</span>
                <span class="font-semibold text-slate-800"><?php echo $inventory_stats['total_items']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Estoque baixo</span>
                <span class="font-semibold text-rose-600"><?php echo $inventory_stats['low_stock_count']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Valor total</span>
                <span class="font-semibold text-slate-800"><?php echo money_br((float)$inventory_stats['total_value']); ?></span>
            </div>
        </div>
        <a class="mt-4 inline-flex text-sm text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=inventory'); ?>">Ver inventario</a>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs uppercase text-slate-500">Clientes</p>
        <p class="mt-4 text-3xl font-semibold text-slate-800"><?php echo $client_count; ?></p>
        <p class="mt-2 text-sm text-slate-500">Clientes cadastrados</p>
        <a class="mt-4 inline-flex text-sm text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=clients'); ?>">Ver clientes</a>
    </div>
</div>



<?php include __DIR__ . '/partials/footer.php'; ?>


