<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Orçamentos</h1>
        <p class="text-sm text-slate-500">Acompanhe propostas enviadas e aprovadas.</p>
    </div>
    <?php if (can_edit()): ?>
        <button type="button" id="open-quote-modal" class="btn-primary inline-flex items-center px-5 py-2.5 text-sm font-semibold transition">Novo Orcamento</button>
        <noscript>
            <a class="btn-primary inline-flex items-center px-5 py-2.5 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=quotes_create'); ?>">Novo Orcamento</a>
        </noscript>
    <?php endif; ?>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="card-surface rounded-2xl overflow-hidden">
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
                        <div class="flex flex-nowrap gap-3">
                            <a href="<?php echo base_url('index.php?action=quotes_view&id=' . $quote['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-blue-50 p-1.5 text-blue-700 hover:bg-blue-100 md:p-2" aria-label="Visualizar" title="Visualizar">
                                <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
                            <?php if (can_edit()): ?>
                                <a href="<?php echo base_url('index.php?action=quotes_edit&id=' . $quote['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-amber-50 p-1.5 text-amber-700 hover:bg-amber-100 md:p-2" aria-label="Editar" title="Editar" data-confirm-link data-confirm-title="Editar orçamento" data-confirm-message="Deseja editar este orçamento?" data-confirm-text="Editar">
                                    <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if (is_admin()): ?>
                                <a href="<?php echo base_url('index.php?action=quotes_delete&id=' . $quote['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-red-50 p-1.5 text-red-700 hover:bg-red-100 md:p-2" aria-label="Excluir" title="Excluir" data-confirm-link data-confirm-title="Excluir orçamento" data-confirm-message="Tem certeza que deseja excluir este orçamento?" data-confirm-text="Excluir" data-confirm-variant="danger">
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
            <?php if (empty($quotes)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">Nenhum orcamento encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (can_edit()): ?>
    <div id="quote-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-quote-modal-close></div>
        <div class="modal-panel relative w-full max-w-2xl mx-4 max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-200">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">Novo Orçamento</h2>
                    <p class="text-sm text-slate-500">Selecione o cliente e inclua itens.</p>
                </div>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-quote-modal-close>Fechar</button>
            </div>

            <form class="space-y-6 px-6 py-6" method="post" action="<?php echo base_url('index.php?action=quotes_store'); ?>">
                <div class="card-surface rounded-2xl p-6 space-y-4">
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

                <div class="card-surface rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Itens</h3>
                        <button type="button" class="btn-secondary px-4 py-2 text-sm font-semibold transition" id="add-item">Adicionar item</button>
                    </div>
                    <div class="mt-4 space-y-3" id="items-container"></div>
                    <div class="mt-6 flex items-center justify-end gap-3 text-lg">
                        <span class="text-slate-500">Total:</span>
                        <span class="font-semibold" id="total-value">R$ 0,00</span>
                    </div>
                </div>

                <div class="flex gap-3 justify-end">
                    <button class="btn-primary px-4 py-2 text-sm font-semibold transition">Salvar Orçamento</button>
                    <button type="button" class="btn-outline px-4 py-2 text-sm font-semibold transition" data-quote-modal-close>Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <template id="item-template">
        <div class="item-row grid gap-3 md:grid-cols-[170px_minmax(0,4fr)_minmax(0,4fr)_64px_100px_40px] items-start">
            <div class="min-w-0">
                <select class="item-type w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" required>
                    <option value="produto">Produto</option>
                    <option value="servico">Servico (mao de obra)</option>
                </select>
            </div>
            <div class="min-w-0 product-field">
                <select class="product-select w-full rounded-lg border border-slate-200 px-4 py-2">
                    <option value="">Selecione o produto</option>
                    <?php foreach ($inventory_items as $product): ?>
                        <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="min-w-0 service-field hidden space-y-2">
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
            <div class="min-w-0">
                <input type="number" min="1" value="1" class="qty-input w-full rounded-lg border border-slate-200 px-2 py-2 text-center" required>
            </div>
            <div class="min-w-0">
                <input type="number" step="0.01" min="0" value="0.00" class="price-input w-full rounded-lg border border-slate-200 px-3 py-2 text-sm bg-slate-50" required readonly>
            </div>
            <div class="flex items-center justify-center">
                <button type="button" class="remove-item inline-flex h-9 w-9 items-center justify-center rounded-md bg-red-50 text-red-700 transition hover:bg-red-100" aria-label="Remover item">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4h8v2"></path>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                    </svg>
                </button>
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
                modal.querySelector('form')?.reset();
                if (window.quoteResetItems) {
                    window.quoteResetItems();
                }
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            window.quoteProducts = <?php echo json_encode($inventory_items); ?>;
            window.quoteServices = <?php echo json_encode($services); ?>;
            window.quoteExistingItems = [];

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
