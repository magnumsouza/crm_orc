<?php include __DIR__ . '/../partials/header.php'; ?>
<?php $is_edit = !empty($quote); ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold"><?php echo $is_edit ? 'Editar Orcamento' : 'Novo Orcamento'; ?></h1>
    <p class="text-sm text-slate-500">Selecione o cliente e inclua itens.</p>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<form class="space-y-6" method="post" action="<?php echo base_url('index.php?action=' . ($is_edit ? 'quotes_update' : 'quotes_store')); ?>">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?php echo $quote['id']; ?>">
    <?php endif; ?>
    <div class="card-surface rounded-2xl p-6 space-y-4">
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Cliente</label>
            <select name="client_id" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
                <option value="">Selecione</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id']; ?>" <?php echo $is_edit && (int)$quote['client_id'] === (int)$client['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($client['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Observacoes adicionais</label>
            <textarea name="notes" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2"><?php echo $is_edit ? htmlspecialchars($quote['notes']) : ''; ?></textarea>
        </div>
    </div>

    <div class="card-surface rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Itens</h2>
            <button type="button" class="btn-secondary px-4 py-2 text-sm font-semibold transition" id="add-item">Adicionar item</button>
        </div>
        <div class="mt-4 space-y-3" id="items-container"></div>
        <div class="mt-6 flex items-center justify-end gap-3 text-lg">
            <span class="text-slate-500">Total:</span>
            <span class="font-semibold" id="total-value">R$ 0,00</span>
        </div>
    </div>

    <div class="flex gap-3">
        <button class="btn-primary px-4 py-2 text-sm font-semibold transition"><?php echo $is_edit ? 'Salvar alteracoes' : 'Salvar Orcamento'; ?></button>
        <a class="btn-outline px-4 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=quotes'); ?>">Voltar</a>
    </div>
</form>

<template id="item-template">
    <div class="item-row grid gap-3 md:grid-cols-12 items-start">
        <div class="md:col-span-2">
            <select class="item-type w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" required>
                <option value="produto">Produto</option>
                <option value="servico">Servico (mao de obra)</option>
            </select>
        </div>
        <div class="md:col-span-4 product-field">
            <select class="product-select w-full rounded-lg border border-slate-200 px-4 py-2" required>
                <option value="">Selecione o produto</option>
                <?php foreach ($inventory_items as $product): ?>
                    <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-4 service-field hidden space-y-2">
            <select class="service-select w-full rounded-lg border border-slate-200 px-4 py-2">
                <option value="">Selecione o servico (opcional)</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo $service['id']; ?>" data-price="<?php echo $service['price']; ?>" data-name="<?php echo htmlspecialchars($service['name']); ?>" data-desc="<?php echo htmlspecialchars($service['description']); ?>">
                        <?php echo htmlspecialchars($service['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" class="service-desc w-full rounded-lg border border-slate-200 px-4 py-2" placeholder="Descricao do servico">
        </div>
        <div class="md:col-span-2">
            <input type="number" min="1" value="1" class="qty-input w-full rounded-lg border border-slate-200 px-4 py-2" required>
        </div>
        <div class="md:col-span-2">
            <input type="number" step="0.01" min="0" value="0.00" class="price-input w-full rounded-lg border border-slate-200 px-3 py-2 text-sm bg-slate-50" required readonly>
        </div>
        <div class="md:col-span-1 text-right">
            <button type="button" class="remove-item btn-danger px-3 py-1 text-xs font-semibold transition">Remover</button>
        </div>
    </div>
</template>

<script src="<?php echo base_url('assets/js/quote.js'); ?>"></script>
<script>
    window.quoteProducts = <?php echo json_encode($inventory_items); ?>;
    window.quoteServices = <?php echo json_encode($services); ?>;
    window.quoteExistingItems = <?php echo json_encode($quote_items ?? []); ?>;
</script>
<?php include __DIR__ . '/../partials/footer.php'; ?>
