<?php $config = require __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Orcamentos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Sora', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        brand: {
                            50: '#f2f7ff',
                            100: '#dbe8ff',
                            300: '#8fb4ff',
                            500: '#4d7dff',
                            700: '#2750d6',
                            900: '#162a7a',
                        },
                        ink: {
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-ink-900 font-display">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <header class="fixed top-0 left-0 right-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
            <div class="flex items-center justify-between px-4 py-4 md:px-6">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-brand-500 text-white grid place-items-center font-semibold">C</div>
                    <div>
                        <p class="text-lg font-semibold leading-tight"><?php echo htmlspecialchars($config['company']['name']); ?></p>
                        <p class="text-xs text-slate-500">CRM de Orcamentos</p>
                    </div>
                </div>
                <div class="hidden items-center text-sm text-slate-600 md:flex">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>
                    <a class="ml-4 text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=logout'); ?>">Sair</a>
                </div>
                <button id="mobileMenuButton" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-700 hover:bg-slate-50 md:hidden" type="button" aria-expanded="false" aria-controls="mobileMenu">
                    Menu
                </button>
            </div>
        </header>
        <div id="mobileMenuOverlay" class="fixed inset-0 z-20 hidden bg-slate-900/30 md:hidden"></div>
        <div id="mobileMenu" class="fixed inset-y-0 left-0 z-30 hidden w-72 translate-x-[-100%] border-r border-slate-200 bg-white px-4 py-6 shadow-lg transition-transform duration-200 md:hidden">
            <?php $is_admin = is_admin(); ?>
            <div class="mb-6 flex items-center justify-between">
                <div class="text-sm text-slate-600"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></div>
                <a class="text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=logout'); ?>">Sair</a>
            </div>
            <nav class="space-y-2 text-sm">
                <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php'); ?>">Dashboard</a>
                <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=clients'); ?>">Clientes</a>
                <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=products'); ?>">Produtos</a>
                <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=quotes'); ?>">Orçamentos</a>
                <?php if ($is_admin): ?>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=quotes_create'); ?>">Novo Orcamento</a>
                <?php endif; ?>
                <hr class="my-3 border-slate-200">
                <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=inventory'); ?>">Estoque</a>
                <?php if ($is_admin): ?>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=inventory_create'); ?>">Novo Produto</a>
                <?php endif; ?>
            </nav>
        </div>
        <div class="flex pt-20">
            <aside class="hidden w-64 min-h-screen border-r border-slate-200 bg-white/70 px-4 py-6 md:block">
                <nav class="space-y-2 text-sm">
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php'); ?>">Dashboard</a>
                    
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=clients'); ?>">Clientes</a>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=products'); ?>">Produtos</a>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=quotes'); ?>">Orçamentos</a>
                    <?php if ($is_admin): ?>
                        <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=quotes_create'); ?>">Novo Orcamento</a>
                    <?php endif; ?>
                    <hr class="my-3 border-slate-200">
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=inventory'); ?>">Estoque</a>
                    <?php if ($is_admin): ?>
                        <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=inventory_create'); ?>">Novo Produto</a>
                    <?php endif; ?>
                </nav>
            </aside>
            <main class="flex-1 px-4 py-6 md:px-8 md:py-8">
