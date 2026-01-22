<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Clientes</h1>
        <p class="text-sm text-slate-500">Gerencie sua base de clientes.</p>
    </div>
    <?php if (is_admin()): ?>
        <a class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700" href="<?php echo base_url('index.php?action=clients_create'); ?>">Novo Cliente</a>
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
    <button class="rounded-lg border border-slate-200 px-4 py-2">Buscar</button>
</form>

<div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
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
                    <td class="px-4 py-3 space-x-3">
                        <?php if (is_admin()): ?>
                            <a class="text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=clients_edit&id=' . $client['id']); ?>">Editar</a>
                        <?php endif; ?>
                        <a class="text-slate-600 hover:text-slate-900" href="<?php echo base_url('index.php?action=clients_history&id=' . $client['id']); ?>">Historico</a>
                        <?php if (is_admin()): ?>
                            <a class="text-red-600 hover:text-red-800" href="<?php echo base_url('index.php?action=clients_delete&id=' . $client['id']); ?>" onclick="return confirm('Excluir cliente?')">Excluir</a>
                        <?php endif; ?>
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
<?php include __DIR__ . '/../partials/footer.php'; ?>
