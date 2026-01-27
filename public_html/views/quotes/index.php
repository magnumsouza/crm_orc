<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Orçamentos</h1>
        <p class="text-sm text-slate-500">Acompanhe propostas enviadas e aprovadas.</p>
    </div>
    <?php if (is_admin()): ?>
        <button type="button" id="open-quote-modal" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700">Novo Orcamento</button>
        <noscript>
            <a class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700" href="<?php echo base_url('index.php?action=quotes_create'); ?>">Novo Orcamento</a>
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
                <th class="text-left px-4 py-3">ID</th>
                <th class="text-left px-4 py-3">Cliente</th>
                <th class="text-left px-4 py-3">Data</th>
                <th class="text-left px-4 py-3">Total</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quotes as $quote): ?>
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">#<?php echo $quote['id']; ?></td>
                    <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($quote['client_name']); ?></td>
                    <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($quote['created_at'])); ?></td>
                    <td class="px-4 py-3"><?php echo money_br($quote['total']); ?></td>
                    <td class="px-4 py-3">
                        <?php if ($quote['status'] === 'Aprovado'): ?>
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">● Aprovado</span>
                        <?php elseif ($quote['status'] === 'Recusado'): ?>
                            <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-red-700">● Recusado</span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-amber-700">● Enviado</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <a class="text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=quotes_view&id=' . $quote['id']); ?>">Ver</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($quotes)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">Nenhum orcamento encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (is_admin()): ?>
    <div id="quote-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-quote-modal-close></div>
        <div class="relative w-full max-w-2xl mx-4 max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-200">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">Novo Orçamento</h2>
                    <p class="text-sm text-slate-500">Selecione o cliente e inclua itens.</p>
                </div>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-quote-modal-close>Fechar</button>
            </div>

            <form class="space-y-6 px-6 py-6" method="post" action="<?php echo base_url('index.php?action=quotes_store'); ?>">
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
                        <h3 class="text-lg font-semibold">Itens</h3>
                        <button type="button" class="rounded-lg border border-slate-200 px-4 py-2" id="add-item">Adicionar item</button>
                    </div>
                    <div class="mt-4 space-y-3" id="items-container"></div>
                    <div class="mt-6 flex items-center justify-end gap-3 text-lg">
                        <span class="text-slate-500">Total:</span>
                        <span class="font-semibold" id="total-value">R$ 0,00</span>
                    </div>
                </div>

                <div class="flex gap-3 justify-end">
                    <button class="rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700">Salvar Orçamento</button>
                    <button type="button" class="rounded-lg border border-slate-200 px-4 py-2" data-quote-modal-close>Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <template id="item-template">
        <div class="grid gap-3 md:grid-cols-12 items-center">
            <div class="md:col-span-5 min-w-0">
                <select class="product-select w-full rounded-lg border border-slate-200 px-4 py-2" required>
                    <option value="">Produto/Servico</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-2 min-w-0">
                <input type="number" min="1" value="1" class="qty-input w-full rounded-lg border border-slate-200 px-4 py-2" required>
            </div>
            <div class="md:col-span-3 min-w-0 text-sm text-slate-600">
                <span class="unit-price">R$ 0,00</span>
            </div>
            <div class="md:col-span-2 flex md:justify-end">
                <button type="button" class="remove-item inline-flex w-full items-center justify-center rounded-md border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 md:w-auto">Remover</button>
            </div>
        </div>
    </template>

    <script src="<?php echo base_url('assets/js/quote.js'); ?>"></script>
    <script>
        (() => {
            const modal = document.getElementById('quote-modal');
            const openBtn = document.getElementById('open-quote-modal');
            const closeButtons = modal ? modal.querySelectorAll('[data-quote-modal-close]') : [];

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
