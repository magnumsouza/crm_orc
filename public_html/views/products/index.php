<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Produtos e Servicos</h1>
        <p class="text-sm text-slate-500">Cadastre seus itens de venda.</p>
    </div>
    <a class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700" href="<?php echo base_url('index.php?action=products_create'); ?>">Novo Produto</a>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="text-left px-4 py-3">Nome</th>
                <th class="text-left px-4 py-3">Descricao</th>
                <th class="text-left px-4 py-3">Preco</th>
                <th class="text-left px-4 py-3">Acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($product['name']); ?></td>
                    <td class="px-4 py-3 text-slate-600"><?php echo htmlspecialchars($product['description']); ?></td>
                    <td class="px-4 py-3"><?php echo money_br($product['price']); ?></td>
                    <td class="px-4 py-3 space-x-3">
                        <a class="text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=products_edit&id=' . $product['id']); ?>">Editar</a>
                        <a class="text-red-600 hover:text-red-800" href="<?php echo base_url('index.php?action=products_delete&id=' . $product['id']); ?>" onclick="return confirm('Excluir produto?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">Nenhum produto encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
