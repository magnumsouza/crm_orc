<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Estoque da oficina</h1>
        <p class="text-sm text-slate-500">Gerencie o inventário de produtos</p>
    </div>
    <?php if (is_admin()): ?>
        <a href="<?php echo base_url('index.php?action=inventory_create'); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Novo Produto
        </a>
    <?php endif; ?>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<!-- Cards de Estatísticas -->
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="rounded-lg bg-white shadow-sm border border-slate-200 p-4">
        <p class="text-sm text-slate-600">Total de Produtos</p>
        <p class="text-3xl font-semibold mt-2"><?php echo $stats['total_items']; ?></p>
    </div>
    <div class="rounded-lg bg-white shadow-sm border border-slate-200 p-4">
        <p class="text-sm text-slate-600">Estoque Baixo</p>
        <p class="text-3xl font-semibold mt-2 <?php echo $stats['low_stock_count'] > 0 ? 'text-red-600' : 'text-green-600'; ?>">
            <?php echo $stats['low_stock_count']; ?>
        </p>
    </div>
    <div class="rounded-lg bg-white shadow-sm border border-slate-200 p-4">
        <p class="text-sm text-slate-600">Valor Total Estoque</p>
        <p class="text-2xl font-semibold mt-2">R$ <?php echo number_format($stats['total_value'], 2, ',', '.'); ?></p>
    </div>
    <div class="rounded-lg bg-white shadow-sm border border-slate-200 p-4">
        <p class="text-sm text-slate-600">Categorias</p>
        <p class="text-3xl font-semibold mt-2"><?php echo count($stats['categories']); ?></p>
    </div>
</div>

<?php if (!empty($low_stock)): ?>
<div class="mb-6 rounded-lg bg-amber-50 border border-amber-200 p-4">
    <h3 class="font-semibold text-amber-900 mb-2">⚠️ Produtos com Estoque Baixo</h3>
    <div class="text-sm text-amber-800 space-y-1">
        <?php foreach (array_slice($low_stock, 0, 5) as $item): ?>
            <div>• <strong><?php echo htmlspecialchars($item['name']); ?></strong> - <?php echo $item['quantity']; ?> unidades (mínimo: <?php echo $item['min_quantity']; ?>)</div>
        <?php endforeach; ?>
        <?php if (count($low_stock) > 5): ?>
            <div class="text-xs mt-2">... e <?php echo count($low_stock) - 5; ?> outros produtos</div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Busca -->
<div class="mb-6">
    <form method="get" class="flex gap-2">
        <input type="hidden" name="action" value="inventory">
        <input type="text" name="search" placeholder="Buscar por nome, SKU ou categoria..." value="<?php echo htmlspecialchars($search); ?>" class="flex-1 rounded-lg border border-slate-200 px-4 py-2">
        <button type="submit" class="px-4 py-2 bg-slate-200 text-slate-900 rounded-lg hover:bg-slate-300">Buscar</button>
    </form>
</div>

<!-- Tabela de Produtos -->
<div class="rounded-lg bg-white shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">SKU</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Produto</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Categoria</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Quantidade</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Preço</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Lucro/Un</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                            Nenhum produto encontrado
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-mono text-slate-900">
                                <?php echo htmlspecialchars($item['sku']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <?php echo htmlspecialchars($item['category']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <?php
                                $qty = (int)$item['quantity'];
                                $min = (int)$item['min_quantity'];
                                $badge_color = $qty < $min ? 'bg-red-100 text-red-800' : ($qty > (int)$item['max_quantity'] ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800');
                                ?>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?php echo $badge_color; ?>">
                                    <?php echo $qty; ?> un
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900">
                                R$ <?php echo number_format($item['price'], 2, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                R$ <?php echo number_format($item['price'] - $item['cost'], 2, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-nowrap gap-3">
                                    <a href="<?php echo base_url('index.php?action=inventory_view&id=' . $item['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-blue-50 p-1.5 text-blue-700 hover:bg-blue-100 md:p-2" aria-label="Visualizar" title="Visualizar">
                                        <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <?php if (is_admin()): ?>
                                        <a href="<?php echo base_url('index.php?action=inventory_edit&id=' . $item['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-amber-50 p-1.5 text-amber-700 hover:bg-amber-100 md:p-2" aria-label="Editar" title="Editar">
                                            <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                            </svg>
                                        </a>
                                        <a href="<?php echo base_url('index.php?action=inventory_delete&id=' . $item['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-red-50 p-1.5 text-red-700 hover:bg-red-100 md:p-2" aria-label="Excluir" title="Excluir" onclick="return confirm('Tem certeza?')">
                                            <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 6h18"></path>
                                                <path d="M8 6V4h8v2"></path>
                                                <path d="M19 6l-1 14H6L5 6"></path>
                                                <path d="M10 11v6"></path>
                                                <path d="M14 11v6"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
