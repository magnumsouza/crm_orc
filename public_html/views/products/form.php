<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold"><?php echo empty($product['id']) ? 'Novo Produto' : 'Editar Produto'; ?></h1>
    <p class="text-sm text-slate-500">Informe nome, descriçao e preço.</p>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<form class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4" method="post" action="<?php echo base_url('index.php?action=' . (empty($product['id']) ? 'products_store' : 'products_update')); ?>">
    <?php if (!empty($product['id'])): ?>
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <?php endif; ?>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Nome</label>
            <input name="name" value="<?php echo htmlspecialchars($product['name']); ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
        </div>
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Preco unitario</label>
            <input name="price" value="<?php echo htmlspecialchars($product['price']); ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
        </div>
    </div>
    <div>
        <label class="text-xs uppercase tracking-wide text-slate-500">Descricao</label>
        <textarea name="description" rows="4" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2"><?php echo htmlspecialchars($product['description']); ?></textarea>
    </div>
    <div class="flex gap-3">
        <button class="rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700">Salvar</button>
        <a class="rounded-lg border border-slate-200 px-4 py-2" href="<?php echo base_url('index.php?action=products'); ?>">Voltar</a>
    </div>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
