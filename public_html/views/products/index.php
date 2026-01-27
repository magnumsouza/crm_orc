<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Produtos e Servicos</h1>
        <p class="text-sm text-slate-500">Cadastre seus itens de venda.</p>
    </div>
    <?php if (is_admin()): ?>
        <button type="button" id="open-product-modal" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700">Novo Produto</button>
        <noscript>
            <a class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700" href="<?php echo base_url('index.php?action=products_create'); ?>">Novo Produto</a>
        </noscript>
    <?php endif; ?>
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
                    <td class="px-4 py-3">
                        <?php if (is_admin()): ?>
                            <div class="flex flex-nowrap gap-3">
                                <a href="<?php echo base_url('index.php?action=products_edit&id=' . $product['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-amber-50 p-1.5 text-amber-700 hover:bg-amber-100 md:p-2" aria-label="Editar" title="Editar">
                                    <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                    </svg>
                                </a>
                                <a href="<?php echo base_url('index.php?action=products_delete&id=' . $product['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-red-50 p-1.5 text-red-700 hover:bg-red-100 md:p-2" aria-label="Excluir" title="Excluir" onclick="return confirm('Excluir produto?')">
                                    <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18"></path>
                                        <path d="M8 6V4h8v2"></path>
                                        <path d="M19 6l-1 14H6L5 6"></path>
                                        <path d="M10 11v6"></path>
                                        <path d="M14 11v6"></path>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
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

<?php if (is_admin()): ?>
    <div id="product-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-product-modal-close></div>
        <div class="relative w-full max-w-2xl mx-4 max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-200">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">Novo Produto</h2>
                    <p class="text-sm text-slate-500">Informe nome, descriÃ§ao e preÃ§o.</p>
                </div>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-product-modal-close>Fechar</button>
            </div>

            <form class="space-y-6 px-6 py-6" method="post" action="<?php echo base_url('index.php?action=products_store'); ?>">
                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
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
                </div>

                <div class="flex flex-wrap gap-3 justify-end">
                    <button class="inline-flex items-center gap-2 rounded-full bg-brand-500 px-5 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-brand-600/30 transition hover:-translate-y-0.5 hover:bg-brand-600 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2">Salvar Produto</button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300 focus-visible:ring-offset-2" data-product-modal-close>Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('product-modal');
            const openBtn = document.getElementById('open-product-modal');
            const closeButtons = modal ? modal.querySelectorAll('[data-product-modal-close]') : [];

            if (!modal || !openBtn) {
                return;
            }

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            openBtn.addEventListener('click', openModal);
            closeButtons.forEach((btn) => btn.addEventListener('click', closeModal));
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        })();
    </script>
<?php endif; ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>
