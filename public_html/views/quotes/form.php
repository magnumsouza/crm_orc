<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Novo Orcamento</h1>
    <p class="text-sm text-slate-500">Selecione o cliente e inclua itens.</p>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<form class="space-y-6" method="post" action="<?php echo base_url('index.php?action=quotes_store'); ?>">
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Cliente</label>
            <select name="client_id" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
                <option value="">Selecione</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Observacoes adicionais</label>
            <textarea name="notes" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2"></textarea>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Itens</h2>
            <button type="button" class="rounded-lg border border-slate-200 px-4 py-2" id="add-item">Adicionar item</button>
        </div>
        <div class="mt-4 space-y-3" id="items-container"></div>
        <div class="mt-6 flex items-center justify-end gap-3 text-lg">
            <span class="text-slate-500">Total:</span>
            <span class="font-semibold" id="total-value">R$ 0,00</span>
        </div>
    </div>

    <div class="flex gap-3">
        <button class="rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700">Salvar Orcamento</button>
        <a class="rounded-lg border border-slate-200 px-4 py-2" href="<?php echo base_url('index.php?action=quotes'); ?>">Voltar</a>
    </div>
</form>

<template id="item-template">
    <div class="grid gap-3 md:grid-cols-12 items-center">
        <div class="md:col-span-6">
            <select class="product-select w-full rounded-lg border border-slate-200 px-4 py-2" required>
                <option value="">Produto/Servico</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <input type="number" min="1" value="1" class="qty-input w-full rounded-lg border border-slate-200 px-4 py-2" required>
        </div>
        <div class="md:col-span-3 text-sm text-slate-600">
            <span class="unit-price">R$ 0,00</span>
        </div>
        <div class="md:col-span-1 text-right">
            <button type="button" class="remove-item text-red-600">Remover</button>
        </div>
    </div>
</template>

<script src="<?php echo base_url('assets/js/quote.js'); ?>"></script>
<script>
    window.quoteProducts = <?php echo json_encode($products); ?>;
</script>
<?php include __DIR__ . '/../partials/footer.php'; ?>
