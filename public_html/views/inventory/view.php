<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold"><?php echo htmlspecialchars($item['name']); ?></h1>
        <p class="text-sm text-slate-500">SKU: <?php echo htmlspecialchars($item['sku']); ?></p>
    </div>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-3 gap-6 mb-6">
    <div class="card-surface rounded-xl p-4">
        <p class="text-sm text-slate-600">Quantidade em Estoque</p>
        <?php
        $qty = (int)$item['quantity'];
        $min = (int)$item['min_quantity'];
        $max = (int)$item['max_quantity'];
        $status_class = $qty < $min ? 'text-red-600' : ($qty > $max ? 'text-amber-600' : 'text-green-600');
        ?>
        <p class="text-3xl font-semibold mt-2 <?php echo $status_class; ?>"><?php echo $qty; ?> un</p>
        <p class="text-xs text-slate-500 mt-2">Mín: <?php echo $min; ?> | Máx: <?php echo $max; ?></p>
    </div>

    <div class="card-surface rounded-xl p-4">
        <p class="text-sm text-slate-600">Preço de Venda</p>
        <p class="text-3xl font-semibold mt-2">R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></p>
        <p class="text-xs text-slate-500 mt-2">Total em Estoque: R$ <?php echo number_format($item['quantity'] * $item['price'], 2, ',', '.'); ?></p>
    </div>

    <div class="card-surface rounded-xl p-4">
        <p class="text-sm text-slate-600">Margem de Lucro/Un</p>
        <p class="text-3xl font-semibold mt-2 text-green-600">R$ <?php echo number_format($item['price'] - $item['cost'], 2, ',', '.'); ?></p>
        <p class="text-xs text-slate-500 mt-2"><?php echo $item['cost'] > 0 ? number_format((($item['price'] - $item['cost']) / $item['cost']) * 100, 1, ',', '.') : '0'; ?>%</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-6 mb-6">
    <div class="card-surface rounded-xl p-4">
        <h3 class="font-semibold mb-4">Informações do Produto</h3>
        <dl class="space-y-3 text-sm">
            <div>
                <dt class="text-slate-600">Categoria</dt>
                <dd class="font-medium"><?php echo htmlspecialchars($item['category']); ?></dd>
            </div>
            <div>
                <dt class="text-slate-600">Custo Unitário</dt>
                <dd class="font-medium">R$ <?php echo number_format($item['cost'], 2, ',', '.'); ?></dd>
            </div>
            <div>
                <dt class="text-slate-600">Valor Total em Custo</dt>
                <dd class="font-medium">R$ <?php echo number_format($item['quantity'] * $item['cost'], 2, ',', '.'); ?></dd>
            </div>
            <div>
                <dt class="text-slate-600">Status</dt>
                <dd class="font-medium"><?php echo htmlspecialchars($item['status']); ?></dd>
            </div>
        </dl>
    </div>

    <div class="card-surface rounded-xl p-4">
        <h3 class="font-semibold mb-4">Dados Complementares</h3>
        <dl class="space-y-3 text-sm">
            <div>
                <dt class="text-slate-600">Criado em</dt>
                <dd class="font-medium"><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></dd>
            </div>
            <div>
                <dt class="text-slate-600">Atualizado em</dt>
                <dd class="font-medium"><?php echo date('d/m/Y H:i', strtotime($item['updated_at'])); ?></dd>
            </div>
            <div>
                <dt class="text-slate-600">Lucro Total em Estoque</dt>
                <dd class="font-medium text-green-600">R$ <?php echo number_format($item['quantity'] * ($item['price'] - $item['cost']), 2, ',', '.'); ?></dd>
            </div>
        </dl>
    </div>
</div>

<?php if (!empty($item['description'])): ?>
<div class="card-surface rounded-xl p-4 mb-6">
    <h3 class="font-semibold mb-3">Descrição</h3>
    <p class="text-slate-700"><?php echo htmlspecialchars($item['description']); ?></p>
</div>
<?php endif; ?>

<div class="flex gap-3">
    <?php if (is_admin()): ?>
        <a href="<?php echo base_url('index.php?action=inventory_edit&id=' . $item['id']); ?>" class="btn-primary px-6 py-2 text-sm font-semibold transition" data-confirm-link data-confirm-title="Editar item do estoque" data-confirm-message="Deseja editar este item?" data-confirm-text="Editar">
            Editar
        </a>
    <?php endif; ?>
    <a href="<?php echo base_url('index.php?action=inventory'); ?>" class="btn-outline px-6 py-2 text-sm font-semibold transition">
        Voltar
    </a>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
