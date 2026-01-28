<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold"><?php echo isset($item['id']) ? 'Editar Produto' : 'Novo Produto'; ?></h1>
        <p class="text-sm text-slate-500"><?php echo isset($item['id']) ? 'Atualize os dados do produto' : 'Adicione um novo produto ao estoque'; ?></p>
    </div>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="card-surface rounded-2xl overflow-hidden">
    <form method="post" action="<?php echo base_url('index.php'); ?>" class="p-6 space-y-6">
        <?php if (isset($item['id'])): ?>
            <input type="hidden" name="action" value="inventory_update">
            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="inventory_store">
        <?php endif; ?>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label for="sku" class="block text-sm font-medium mb-2">SKU *</label>
                <input type="text" name="sku" id="sku" required value="<?php echo htmlspecialchars($item['sku'] ?? ''); ?>" class="w-full rounded-lg border border-slate-200 px-4 py-2" placeholder="Ex: SKU-001">
            </div>
            <div>
                <label for="name" class="block text-sm font-medium mb-2">Nome do Produto *</label>
                <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" class="w-full rounded-lg border border-slate-200 px-4 py-2">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium mb-2">Descrição</label>
            <textarea name="description" id="description" rows="3" class="w-full rounded-lg border border-slate-200 px-4 py-2"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label for="quantity" class="block text-sm font-medium mb-2">Quantidade em Estoque *</label>
                <input type="number" name="quantity" id="quantity" required value="<?php echo (int)($item['quantity'] ?? 0); ?>" class="w-full rounded-lg border border-slate-200 px-4 py-2" min="0">
            </div>
            <div>
                <label for="price" class="block text-sm font-medium mb-2">Preço de Venda *</label>
                <div class="relative">
                    <span class="absolute left-4 top-2 text-slate-500">R$</span>
                    <input type="number" name="price" id="price" required value="<?php echo (float)($item['price'] ?? 0); ?>" step="0.01" class="w-full rounded-lg border border-slate-200 px-4 py-2 pl-10" min="0">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label for="cost" class="block text-sm font-medium mb-2">Custo Unitário *</label>
                <div class="relative">
                    <span class="absolute left-4 top-2 text-slate-500">R$</span>
                    <input type="number" name="cost" id="cost" required value="<?php echo (float)($item['cost'] ?? 0); ?>" step="0.01" class="w-full rounded-lg border border-slate-200 px-4 py-2 pl-10" min="0">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Margem de Lucro</label>
                <div class="rounded-lg border border-slate-200 px-4 py-2 bg-slate-50 text-slate-600">
                    <span id="margin">-</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label for="min_quantity" class="block text-sm font-medium mb-2">Quantidade Mínima</label>
                <input type="number" name="min_quantity" id="min_quantity" value="<?php echo (int)($item['min_quantity'] ?? 5); ?>" class="w-full rounded-lg border border-slate-200 px-4 py-2" min="0">
            </div>
            <div>
                <label for="max_quantity" class="block text-sm font-medium mb-2">Quantidade Máxima</label>
                <input type="number" name="max_quantity" id="max_quantity" value="<?php echo (int)($item['max_quantity'] ?? 100); ?>" class="w-full rounded-lg border border-slate-200 px-4 py-2" min="0">
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="btn-primary px-6 py-2 text-sm font-semibold transition">
                <?php echo isset($item['id']) ? 'Atualizar' : 'Adicionar ao Estoque'; ?>
            </button>
            <a href="<?php echo base_url('index.php?action=inventory'); ?>" class="btn-outline px-6 py-2 text-sm font-semibold transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('price').addEventListener('change', calculateMargin);
document.getElementById('cost').addEventListener('change', calculateMargin);

function calculateMargin() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const cost = parseFloat(document.getElementById('cost').value) || 0;
    const margin = price - cost;
    const percent = cost > 0 ? ((margin / cost) * 100).toFixed(1) : 0;
    document.getElementById('margin').textContent = 'R$ ' + margin.toFixed(2).replace('.', ',') + ' (' + percent + '%)';
}

calculateMargin();
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
