<?php include __DIR__ . '/../partials/header.php'; ?>
<?php $is_edit = !empty($service); ?>
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold"><?php echo $is_edit ? 'Editar Serviço' : 'Novo Serviço'; ?></h1>
        <p class="text-sm text-slate-500">Defina valores de mão de obra.</p>
    </div>
    <a class="btn-outline px-4 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=services'); ?>">Voltar</a>
</div>

<?php if (!empty($flash)): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<form class="space-y-6 card-surface rounded-2xl p-6" method="post" action="<?php echo base_url('index.php?action=' . ($is_edit ? 'services_update' : 'services_store')); ?>">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
    <?php endif; ?>

    <div>
        <label class="text-xs uppercase tracking-wide text-slate-500">Nome do serviço</label>
        <input name="name" value="<?php echo $is_edit ? htmlspecialchars($service['name']) : ''; ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
    </div>

    <div>
        <label class="text-xs uppercase tracking-wide text-slate-500">Descrição</label>
        <textarea name="description" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" placeholder="Detalhe o serviço ou mão de obra"><?php echo $is_edit ? htmlspecialchars($service['description']) : ''; ?></textarea>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Valor (R$)</label>
            <input name="price" type="number" step="0.01" min="0" value="<?php echo $is_edit ? (float)$service['price'] : 0; ?>" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2" required>
        </div>
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Status</label>
            <select name="status" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2">
                <option value="Ativo" <?php echo !$is_edit || $service['status'] === 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                <option value="Inativo" <?php echo $is_edit && $service['status'] === 'Inativo' ? 'selected' : ''; ?>>Inativo</option>
            </select>
        </div>
    </div>

    <div class="flex gap-3 justify-end">
        <button class="btn-primary px-4 py-2 text-sm font-semibold transition"><?php echo $is_edit ? 'Salvar' : 'Cadastrar Serviço'; ?></button>
        <a class="btn-outline px-4 py-2 text-sm font-semibold transition" href="<?php echo base_url('index.php?action=services'); ?>">Cancelar</a>
    </div>
</form>

<?php include __DIR__ . '/../partials/footer.php'; ?>
