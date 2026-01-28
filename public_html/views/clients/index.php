<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Clientes</h1>
        <p class="text-sm text-slate-500">Gerencie sua base de clientes.</p>
    </div>
    <?php if (is_admin()): ?>
        <button type="button" id="open-client-modal" class="btn-primary inline-flex items-center px-5 py-2.5 text-sm font-semibold transition">Novo Cliente</button>
        <noscript>
            <a class="btn-primary inline-flex items-center px-5 py-2.5 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=clients_create'); ?>">Novo Cliente</a>
        </noscript>
    <?php endif; ?>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<form class="mb-6 flex gap-3" method="get" action="<?php echo base_url('index.php'); ?>">
    <input type="hidden" name="action" value="clients">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar por nome" class="w-full rounded-lg border border-slate-200 px-4 py-2">
    <button class="btn-outline px-4 py-2 text-sm font-semibold transition">Buscar</button>
</form>

<div class="card-surface rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="text-left px-4 py-3">Nome</th>
                <th class="text-left px-4 py-3">Email</th>
                <th class="text-left px-4 py-3">Telefone</th>
                <th class="text-left px-4 py-3">Empresa</th>
                <th class="text-left px-4 py-3">Acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($client['name']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($client['email']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($client['phone']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($client['company']); ?></td>
                    <td class="px-4 py-3">
                        <div class="flex flex-nowrap gap-3">
                            <?php if (is_admin()): ?>
                                <a href="<?php echo base_url('index.php?action=clients_edit&id=' . $client['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-amber-50 p-1.5 text-amber-700 hover:bg-amber-100 md:p-2" aria-label="Editar" title="Editar" data-confirm-link data-confirm-title="Editar cliente" data-confirm-message="Deseja editar este cliente agora?" data-confirm-text="Editar">
                                    <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo base_url('index.php?action=clients_history&id=' . $client['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-blue-50 p-1.5 text-blue-700 hover:bg-blue-100 md:p-2" aria-label="Historico" title="Historico">
                                <svg class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 12a9 9 0 1 0 3-6.7"></path>
                                    <path d="M3 4v4h4"></path>
                                    <path d="M12 7v5l3 3"></path>
                                </svg>
                            </a>
                            <?php if (is_admin()): ?>
                                <a href="<?php echo base_url('index.php?action=clients_delete&id=' . $client['id']); ?>" class="inline-flex items-center justify-center rounded-md bg-red-50 p-1.5 text-red-700 hover:bg-red-100 md:p-2" aria-label="Excluir" title="Excluir" data-confirm-link data-confirm-title="Excluir cliente" data-confirm-message="Tem certeza que deseja excluir este cliente?" data-confirm-text="Excluir" data-confirm-variant="danger">
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
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (is_admin()): ?>
    <div id="client-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-client-modal-close></div>
        <div class="modal-panel relative w-full max-w-2xl mx-4 max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-200">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">Novo Cliente</h2>
                    <p class="text-sm text-slate-500">Preencha os dados do cliente.</p>
                </div>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-client-modal-close>Fechar</button>
            </div>

            <form class="space-y-6 px-6 py-6" method="post" action="<?php echo base_url('index.php?action=clients_store'); ?>">
                <div class="card-surface rounded-2xl p-6 space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Nome</label>
                            <input name="name" value="<?php echo htmlspecialchars($client['name']); ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Email</label>
                            <input name="email" type="email" value="<?php echo htmlspecialchars($client['email']); ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Telefone</label>
                            <input name="phone" value="<?php echo htmlspecialchars($client['phone']); ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2">
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Empresa (opcional)</label>
                            <input name="company" value="<?php echo htmlspecialchars($client['company']); ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-wide text-slate-500">Observacoes</label>
                        <textarea name="notes" rows="4" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2"><?php echo htmlspecialchars($client['notes']); ?></textarea>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 justify-end">
                    <button class="btn-primary inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold transition">Salvar Cliente</button>
                    <button type="button" class="btn-outline inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold transition" data-client-modal-close>Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('client-modal');
            const openBtn = document.getElementById('open-client-modal');
            const closeButtons = modal ? modal.querySelectorAll('[data-client-modal-close]') : [];

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
