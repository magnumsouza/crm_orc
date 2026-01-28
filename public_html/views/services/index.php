<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Serviços</h1>
        <p class="text-sm text-slate-500">Cadastre valores de mão de obra e serviços.</p>
    </div>
    <?php if (is_admin()): ?>
        <button type="button" id="open-service-modal" class="btn-primary inline-flex items-center px-5 py-2.5 text-sm font-semibold transition">Novo Serviço</button>
        <noscript>
            <a class="btn-primary inline-flex items-center px-5 py-2.5 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=services_create'); ?>">Novo Serviço</a>
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
                <th class="text-left px-4 py-3">Serviço</th>
                <th class="text-left px-4 py-3">Descrição</th>
                <th class="text-left px-4 py-3">Valor</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $row): ?>
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="px-4 py-3 text-slate-600"><?php echo htmlspecialchars($row['description']); ?></td>
                    <td class="px-4 py-3"><?php echo money_br($row['price']); ?></td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs <?php echo $row['status'] === 'Ativo' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'; ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-nowrap gap-3">
                            <?php if (is_admin()): ?>
                                <a href="<?php echo base_url('index.php?action=services_edit&id=' . $row['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-amber-50 p-1.5 text-amber-700 hover:bg-amber-100 md:p-2" aria-label="Editar" title="Editar" data-confirm-link data-confirm-title="Editar servico" data-confirm-message="Deseja editar este servico?" data-confirm-text="Editar">
                                    <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                    </svg>
                                </a>
                                <a href="<?php echo base_url('index.php?action=services_delete&id=' . $row['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-red-50 p-1.5 text-red-700 hover:bg-red-100 md:p-2" aria-label="Excluir" title="Excluir" data-confirm-link data-confirm-title="Excluir servico" data-confirm-message="Tem certeza que deseja excluir este servico?" data-confirm-text="Excluir" data-confirm-variant="danger">
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
            <?php if (empty($services)): ?>
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Nenhum servico cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (is_admin()): ?>
    <div id="service-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-service-modal-close></div>
        <div class="modal-panel relative w-full max-w-xl mx-4 max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-200">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">Novo Serviço</h2>
                    <p class="text-sm text-slate-500">Informe o valor da mão de obra.</p>
                </div>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-service-modal-close>Fechar</button>
            </div>

            <form class="space-y-6 px-6 py-6" method="post" action="<?php echo base_url('index.php?action=services_store'); ?>">
                <div class="space-y-4">
                    <div>
                        <label class="text-xs uppercase tracking-wide text-slate-500">Nome do serviço</label>
                        <input name="name" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-wide text-slate-500">Descrição</label>
                        <textarea name="description" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" placeholder="Detalhe o serviço ou mão de obra"></textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Valor (R$)</label>
                            <input name="price" type="number" step="0.01" min="0" value="<?php echo (float)$service['price']; ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Status</label>
                            <select name="status" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2">
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 justify-end">
                    <button class="btn-primary inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold transition">Salvar Serviço</button>
                    <button type="button" class="btn-outline inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold transition" data-service-modal-close>Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('service-modal');
            const openBtn = document.getElementById('open-service-modal');
            const closeButtons = modal ? modal.querySelectorAll('[data-service-modal-close]') : [];

            if (!modal || !openBtn) {
                return;
            }

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.querySelector('form')?.reset();
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
