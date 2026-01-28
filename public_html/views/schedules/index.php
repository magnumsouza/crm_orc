<?php include __DIR__ . '/../partials/header.php'; ?>
<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold">Agendamentos</h2>
        <p class="text-sm text-slate-500">Gerencie servicos agendados e status em tempo real.</p>
    </div>
    <?php if (is_admin()): ?>
        <button type="button" id="open-schedule-modal" class="btn-primary inline-flex items-center px-5 py-2.5 text-sm font-semibold transition">Novo Agendamento</button>
        <noscript>
            <a class="btn-primary inline-flex items-center px-5 py-2.5 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=schedules_create'); ?>">Novo Agendamento</a>
        </noscript>
    <?php endif; ?>
</div>

<div class="mt-6 grid gap-4 md:grid-cols-5">
    <div class="card-surface rounded-2xl p-4">
        <p class="text-xs uppercase text-slate-500">Total</p>
        <p class="mt-2 text-2xl font-semibold text-slate-800"><?php echo $counts['total']; ?></p>
    </div>
    <div class="card-surface rounded-2xl p-4">
        <p class="text-xs uppercase text-slate-500">Agendados</p>
        <p class="mt-2 text-2xl font-semibold text-blue-600"><?php echo $counts['agendados']; ?></p>
    </div>
    <div class="card-surface rounded-2xl p-4">
        <p class="text-xs uppercase text-slate-500">Confirmados</p>
        <p class="mt-2 text-2xl font-semibold text-amber-600"><?php echo $counts['confirmados']; ?></p>
    </div>
    <div class="card-surface rounded-2xl p-4">
        <p class="text-xs uppercase text-slate-500">Concluidos</p>
        <p class="mt-2 text-2xl font-semibold text-emerald-600"><?php echo $counts['concluidos']; ?></p>
    </div>
    <div class="card-surface rounded-2xl p-4">
        <p class="text-xs uppercase text-slate-500">Cancelados</p>
        <p class="mt-2 text-2xl font-semibold text-rose-600"><?php echo $counts['cancelados']; ?></p>
    </div>
</div>

<div class="mt-6 card-surface rounded-2xl p-4">
    <form class="mb-4 flex flex-wrap items-center gap-3" method="get" action="<?php echo base_url('index.php'); ?>">
        <input type="hidden" name="action" value="schedules">
        <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm md:w-80" type="text" name="q" placeholder="Buscar por cliente ou servico" value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn-outline px-4 py-2 text-sm font-semibold transition" type="submit">Buscar</button>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-slate-500">
                <tr>
                    <th class="py-2">Data</th>
                    <th class="py-2">Hora</th>
                    <th class="py-2">Cliente</th>
                    <th class="py-2">Servico</th>
                    <th class="py-2">Status</th>
                    <th class="py-2 text-right">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($schedules as $schedule): ?>
                    <tr class="align-top">
                        <td class="py-3"><?php echo date('d/m/Y', strtotime($schedule['scheduled_date'])); ?></td>
                        <td class="py-3"><?php echo substr($schedule['scheduled_time'], 0, 5); ?></td>
                        <td class="py-3 font-medium text-slate-800"><?php echo htmlspecialchars($schedule['client_name']); ?></td>
                        <td class="py-3 text-slate-600">
                            <?php
                            $service = $schedule['service_description'];
                            echo htmlspecialchars(strlen($service) > 50 ? substr($service, 0, 50) . '...' : $service);
                            ?>
                        </td>
                        <td class="py-3">
                            <?php if (is_admin()): ?>
                                <form method="post" action="<?php echo base_url('index.php?action=schedules_update_status'); ?>">
                                    <input type="hidden" name="id" value="<?php echo $schedule['id']; ?>">
                                    <select name="status" class="rounded-lg border border-slate-200 px-2 py-1 text-xs">
                                        <?php foreach (['Agendado', 'Confirmado', 'Concluido', 'Cancelado'] as $status): ?>
                                            <option value="<?php echo $status; ?>" <?php echo $schedule['status'] === $status ? 'selected' : ''; ?>>
                                                <?php echo $status; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="ml-2 text-xs text-brand-700 hover:text-brand-900" type="submit">Atualizar</button>
                                </form>
                            <?php else: ?>
                                <span class="inline-flex rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-700"><?php echo $schedule['status']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="<?php echo base_url('index.php?action=schedules_view&id=' . $schedule['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-blue-50 p-1.5 text-blue-700 hover:bg-blue-100 md:p-2" aria-label="Visualizar" title="Visualizar">
                                    <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                <?php if (is_admin()): ?>
                                    <a href="<?php echo base_url('index.php?action=schedules_edit&id=' . $schedule['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-amber-50 p-1.5 text-amber-700 hover:bg-amber-100 md:p-2" aria-label="Editar" title="Editar" data-confirm-link data-confirm-title="Editar agendamento" data-confirm-message="Deseja editar este agendamento?" data-confirm-text="Editar">
                                        <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($schedules)): ?>
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-500">Nenhum agendamento encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>

<?php if (is_admin()): ?>
    <div id="schedule-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-schedule-modal-close></div>
        <div class="modal-panel relative w-full max-w-3xl mx-4 max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-200">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">Novo Agendamento</h2>
                    <p class="text-sm text-slate-500">Preencha os dados e inclua os itens do estoque.</p>
                </div>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-schedule-modal-close>Fechar</button>
            </div>

            <form class="space-y-6 px-6 py-6" method="post" action="<?php echo base_url('index.php?action=schedules_store'); ?>" data-slots-url="<?php echo base_url('index.php?action=schedules_api_slots'); ?>">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Cliente</label>
                        <select class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="client_id" required>
                            <option value="">Selecione</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Servico</label>
                        <textarea class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="service_description" rows="3" required></textarea>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Data</label>
                        <input class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="date" name="scheduled_date" data-schedule-date required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Horario</label>
                        <select class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="scheduled_time" data-schedule-time required>
                            <option value="">Selecione uma data</option>
                        </select>
                        <p class="mt-2 text-xs text-slate-500" data-slots-message>Selecione uma data para ver os horarios livres.</p>
                    </div>
                </div>

                <div class="card-surface rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Itens de estoque</h3>
                            <p class="text-xs text-slate-500">Adicione produtos/pecas usados no servico.</p>
                        </div>
                        <button class="btn-outline px-3 py-2 text-xs font-semibold transition" type="button" data-add-item>Adicionar item</button>
                    </div>

                    <div class="mt-4 space-y-3" data-items-container>
                        <p class="text-sm text-slate-500" data-empty-items>Nenhum item adicionado.</p>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Observacoes</label>
                    <textarea class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="notes" rows="3"></textarea>
                </div>

                <div class="card-surface rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Nota Fiscal</h3>
                            <p class="text-xs text-slate-500">Selecione o tipo e o modo de preenchimento.</p>
                        </div>
                    </div>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Tipo</label>
                            <select name="invoice_type" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" data-invoice-type>
                                <option value="">Nao emitir</option>
                                <option value="NFE">NFe</option>
                                <option value="NFSE">NFS-e</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Modo</label>
                            <select name="invoice_mode" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" data-invoice-mode>
                                <option value="cadastro">Cliente/Produtos cadastrados</option>
                                <option value="avulsa">Nota avulsa (manual)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 space-y-4" data-invoice-avulsa hidden>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs uppercase tracking-wide text-slate-500">Cliente (manual)</label>
                                <input name="invoice_client_name" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Nome/Razao social">
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-wide text-slate-500">CPF/CNPJ</label>
                                <input name="invoice_client_document" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Documento">
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-wide text-slate-500">Email</label>
                                <input name="invoice_client_email" type="email" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="email@exemplo.com">
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-wide text-slate-500">Telefone</label>
                                <input name="invoice_client_phone" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="(00) 00000-0000">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Endereco</label>
                            <textarea name="invoice_client_address" rows="2" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Endereco completo"></textarea>
                        </div>

                        <div class="card-surface rounded-2xl p-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-slate-800">Itens avulsos</h4>
                                <button type="button" class="btn-outline px-3 py-2 text-xs font-semibold transition" data-add-invoice-item>Adicionar item</button>
                            </div>
                            <div class="mt-4 space-y-3" data-invoice-items>
                                <p class="text-sm text-slate-500" data-empty-invoice>Sem itens adicionados.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 justify-end">
                    <button class="btn-primary px-4 py-2 text-sm font-semibold transition">Agendar</button>
                    <button type="button" class="btn-outline px-4 py-2 text-sm font-semibold transition" data-schedule-modal-close>Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <template id="scheduleItemTemplate">
        <div class="grid gap-3 md:grid-cols-[1fr_120px_40px] items-end" data-item-row>
            <div>
                <label class="text-xs font-medium text-slate-600">Item</label>
                <select class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="items[][inventory_id]" data-item-select required>
                    <option value="">Selecione</option>
                    <?php foreach ($inventory as $stock): ?>
                        <option value="<?php echo $stock['id']; ?>" data-available="<?php echo $stock['quantity']; ?>">
                            <?php echo htmlspecialchars($stock['name']); ?> (<?php echo $stock['quantity']; ?> disponivel)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-1 text-xs text-slate-500" data-stock-label></p>
            </div>
            <div>
                <label class="text-xs font-medium text-slate-600">Quantidade</label>
                <input class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="number" min="1" name="items[][quantity]" value="1" required>
            </div>
            <button class="btn-danger px-3 py-1 text-xs font-semibold transition" type="button" data-remove-item>Remover</button>
        </div>
    </template>

    <template id="invoiceItemTemplate">
        <div class="grid gap-3 md:grid-cols-[1fr_120px_140px_40px] items-end" data-invoice-row>
            <div>
                <label class="text-xs font-medium text-slate-600">Descricao</label>
                <input class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" name="invoice_items[][description]" required>
            </div>
            <div>
                <label class="text-xs font-medium text-slate-600">Quantidade</label>
                <input class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="number" min="1" name="invoice_items[][quantity]" value="1" required>
            </div>
            <div>
                <label class="text-xs font-medium text-slate-600">Valor unitario</label>
                <input class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="number" step="0.01" min="0" name="invoice_items[][unit_price]" value="0">
            </div>
            <button class="btn-danger px-3 py-1 text-xs font-semibold transition" type="button" data-remove-invoice>Remover</button>
        </div>
    </template>

    <script src="<?php echo base_url('assets/js/schedules.js'); ?>"></script>
    <script>
        (() => {
            const modal = document.getElementById('schedule-modal');
            const openBtn = document.getElementById('open-schedule-modal');
            const closeButtons = modal ? modal.querySelectorAll('[data-schedule-modal-close]') : [];
            const invoiceMode = modal ? modal.querySelector('[data-invoice-mode]') : null;
            const invoiceType = modal ? modal.querySelector('[data-invoice-type]') : null;
            const avulsaBox = modal ? modal.querySelector('[data-invoice-avulsa]') : null;
            const addInvoiceItem = modal ? modal.querySelector('[data-add-invoice-item]') : null;
            const invoiceItems = modal ? modal.querySelector('[data-invoice-items]') : null;
            const invoiceTemplate = document.getElementById('invoiceItemTemplate');
            const emptyInvoice = modal ? modal.querySelector('[data-empty-invoice]') : null;

            if (!modal || !openBtn) {
                return;
            }

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.querySelector('form')?.reset();
                if (avulsaBox) avulsaBox.hidden = true;
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

            const toggleInvoice = () => {
                if (!invoiceType || !invoiceMode || !avulsaBox) return;
                if (invoiceType.value === '' || invoiceMode.value !== 'avulsa') {
                    avulsaBox.hidden = true;
                } else {
                    avulsaBox.hidden = false;
                }
            };

            if (invoiceType) invoiceType.addEventListener('change', toggleInvoice);
            if (invoiceMode) invoiceMode.addEventListener('change', toggleInvoice);

            if (addInvoiceItem && invoiceItems && invoiceTemplate) {
                addInvoiceItem.addEventListener('click', () => {
                    if (emptyInvoice) emptyInvoice.classList.add('hidden');
                    const clone = document.importNode(invoiceTemplate.content, true);
                    const row = clone.querySelector('[data-invoice-row]');
                    if (row) {
                        const removeBtn = row.querySelector('[data-remove-invoice]');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', () => {
                                row.remove();
                                if (invoiceItems.querySelectorAll('[data-invoice-row]').length === 0 && emptyInvoice) {
                                    emptyInvoice.classList.remove('hidden');
                                }
                            });
                        }
                    }
                    invoiceItems.appendChild(clone);
                });
            }
        })();
    </script>
<?php endif; ?>
