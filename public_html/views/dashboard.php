<?php include __DIR__ . '/partials/header.php'; ?>
<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>

    
<?php endif; ?>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Estoque</p>
        <p class="mt-3 text-3xl font-semibold text-slate-900"><?php echo $inventory_stats['total_items']; ?></p>
        <p class="mt-2 text-sm text-slate-500">Itens cadastrados</p>
        <p class="mt-3 text-lg font-semibold text-emerald-600"><?php echo money_br((float)$inventory_stats['total_value']); ?></p>
        <p class="text-xs text-slate-500">Valor em estoque</p>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Serviços</p>
        <p class="mt-3 text-3xl font-semibold text-slate-900"><?php echo $service_count; ?></p>
        <p class="mt-2 text-sm text-slate-500">Serviços ativos</p>
        <p class="mt-3 text-lg font-semibold text-emerald-600"><?php echo money_br($service_total); ?></p>
        <p class="text-xs text-slate-500">Soma dos serviços</p>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Futuros</p>
        <p class="mt-3 text-3xl font-semibold text-slate-900"><?php echo $futuros; ?></p>
        <p class="mt-2 text-sm text-slate-500">Orçamentos aprovados</p>
        <p class="mt-3 text-lg font-semibold text-emerald-600"><?php echo money_br($futuros_total); ?></p>
        <p class="text-xs text-slate-500">Total aprovado</p>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Notas fiscais</p>
        <p class="mt-3 text-3xl font-semibold text-slate-900"><?php echo $total_invoices; ?></p>
        <p class="mt-2 text-sm text-slate-500">Emitidas</p>
        <p class="mt-3 text-lg font-semibold text-emerald-600"><?php echo money_br($total_invoices_value); ?></p>
        <p class="text-xs text-slate-500">Total emitido</p>
    </div>
</div>

<div class="mt-8"></div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Orçamentos criados</p>
        <p class="mt-4 text-3xl font-semibold text-brand-700"><?php echo $counts['total']; ?></p>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Aprovados</p>
        <p class="mt-4 text-3xl font-semibold text-emerald-600"><?php echo $counts['approved']; ?></p>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Recusados</p>
        <p class="mt-4 text-3xl font-semibold text-red-500"><?php echo $counts['rejected']; ?></p>
    </div>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Agendamentos</p>
        <div class="mt-4 space-y-2 text-sm text-slate-600">
            <div class="flex items-center justify-between">
                <span>Total</span>
                <span class="font-semibold text-slate-800"><?php echo $schedule_counts['total']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Agendados</span>
                <span class="font-semibold text-blue-600"><?php echo $schedule_counts['agendados']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Confirmados</span>
                <span class="font-semibold text-amber-600"><?php echo $schedule_counts['confirmados']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Concluidos</span>
                <span class="font-semibold text-emerald-600"><?php echo $schedule_counts['concluidos']; ?></span>
            </div>
        </div>
        <a class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white" href="<?php echo base_url('index.php?action=schedules'); ?>">
            Ver agendamentos
            <span aria-hidden="true">→</span>
        </a>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Inventario</p>
        <div class="mt-4 space-y-2 text-sm text-slate-600">
            <div class="flex items-center justify-between">
                <span>Itens ativos</span>
                <span class="font-semibold text-slate-800"><?php echo $inventory_stats['total_items']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Estoque baixo</span>
                <span class="font-semibold text-rose-600"><?php echo $inventory_stats['low_stock_count']; ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span>Valor total</span>
                <span class="font-semibold text-slate-800"><?php echo money_br((float)$inventory_stats['total_value']); ?></span>
            </div>
        </div>
        <a class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white" href="<?php echo base_url('index.php?action=inventory'); ?>">
            Ver inventario
            <span aria-hidden="true">→</span>
        </a>
    </div>
    <div class="card-surface rounded-2xl p-6">
        <p class="text-xs uppercase text-slate-500">Clientes</p>
        <p class="mt-4 text-3xl font-semibold text-slate-800"><?php echo $client_count; ?></p>
        <p class="mt-2 text-sm text-slate-500">Clientes cadastrados</p>
        <a class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white" href="<?php echo base_url('index.php?action=clients'); ?>">
            Ver clientes
            <span aria-hidden="true">→</span>
        </a>
    </div>
</div>



<?php if (can_edit()): ?>
    <div id="quote-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-quote-modal-close></div>
        <div class="modal-panel relative w-full max-w-2xl mx-4 max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-200">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">Novo Orcamento</h2>
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
                        <button type="button" class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold transition" id="add-item">Adicionar item</button>
                    </div>
                    <div class="mt-4 space-y-3" id="items-container"></div>
                    <div class="mt-6 flex items-center justify-end gap-3 text-lg">
                        <span class="text-slate-500">Total:</span>
                        <span class="font-semibold" id="total-value">R$ 0,00</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 justify-end">
                    <button class="btn-primary inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold transition">Salvar Orcamento</button>
                    <button type="button" class="btn-outline inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold transition" data-quote-modal-close>Cancelar</button>
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
        window.quoteProducts = <?php echo json_encode($inventory_items); ?>;
        window.quoteServices = <?php echo json_encode($services); ?>;
        window.quoteExistingItems = [];

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

            openBtn.addEventListener('click', openModal);
            closeButtons.forEach((btn) => btn.addEventListener('click', closeModal));
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        })();
    </script>
<?php endif; ?><?php include __DIR__ . '/partials/footer.php'; ?>
