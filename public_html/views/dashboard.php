<?php include __DIR__ . '/partials/header.php'; ?>
<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
        <p class="text-xs uppercase text-slate-500">Orcamentos criados</p>
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

<div class="mt-8 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold">Comece um novo orcamento</h2>
        <p class="text-sm text-slate-500">Crie propostas com itens e envie rapidamente.</p>
    </div>
    <a class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700" href="<?php echo base_url('index.php?action=quotes_create'); ?>">Novo Orcamento</a>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
