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
            darkMode: 'class',
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
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            if (stored === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <style>
        .dark body { background-color: #0b1220; color: #e2e8f0; }
        .dark .bg-slate-50 { background-color: #0b1220 !important; }
        .dark .bg-white { background-color: #0f172a !important; }
        .dark .bg-white\/80 { background-color: rgba(15, 23, 42, 0.8) !important; }
        .dark .bg-white\/70 { background-color: rgba(15, 23, 42, 0.7) !important; }
        .dark .text-slate-500 { color: #9aa9bf !important; }
        .dark .text-slate-600 { color: #d0d9ea !important; }
        .dark .text-slate-700 { color: #e7edf7 !important; }
        .dark .text-slate-800 { color: #f4f7fb !important; }
        .dark .text-slate-900 { color: #f8fafc !important; }
        .dark .text-ink-900 { color: #e2e8f0 !important; }
        .dark .border-slate-200 { border-color: #1e293b !important; }
        .dark .border-slate-100 { border-color: #1e293b !important; }
        .dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]) { border-color: #1f2937 !important; }
        .dark .divide-slate-200 > :not([hidden]) ~ :not([hidden]) { border-color: #1f2937 !important; }
        .dark table { color: #e2e8f0; }
        .dark thead { color: #cbd5f5; }
        .dark .bg-brand-50 { background-color: #0b1b3a !important; }
        .dark .bg-gradient-to-br { background-image: linear-gradient(to bottom right, #0b1220, #0f172a, #111827) !important; }
    </style>
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
                    <button id="themeToggle" type="button" class="mr-4 inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" aria-label="Alternar tema">
                        <span class="hidden dark:inline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 18a6 6 0 100-12 6 6 0 000 12z"/>
                                <path d="M12 2.75a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5A.75.75 0 0112 2.75zM12 20.5a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5a.75.75 0 01-.75-.75zM4.22 5.47a.75.75 0 011.06-1.06l.35.36a.75.75 0 11-1.06 1.06l-.35-.36zM18.37 19.62a.75.75 0 011.06-1.06l.35.36a.75.75 0 11-1.06 1.06l-.35-.36zM2.75 12a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5A.75.75 0 012.75 12zM20.5 12a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5a.75.75 0 01-.75-.75zM5.47 19.78a.75.75 0 011.06 0l.36.35a.75.75 0 11-1.06 1.06l-.36-.35a.75.75 0 010-1.06zM19.62 5.63a.75.75 0 011.06 0l.35.36a.75.75 0 11-1.06 1.06l-.35-.36a.75.75 0 010-1.06z"/>
                            </svg>
                        </span>
                        <span class="dark:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M21.75 14.5A8.25 8.25 0 1110 2.25a.75.75 0 01.7 1.03 6.75 6.75 0 009.02 9.02.75.75 0 011.03.7z"/>
                            </svg>
                        </span>
                    </button>
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
                <div class="flex items-center gap-3">
                    <button id="themeToggleMobile" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" aria-label="Alternar tema">
                        <span class="hidden dark:inline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 18a6 6 0 100-12 6 6 0 000 12z"/>
                                <path d="M12 2.75a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5A.75.75 0 0112 2.75zM12 20.5a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5a.75.75 0 01-.75-.75zM4.22 5.47a.75.75 0 011.06-1.06l.35.36a.75.75 0 11-1.06 1.06l-.35-.36zM18.37 19.62a.75.75 0 011.06-1.06l.35.36a.75.75 0 11-1.06 1.06l-.35-.36zM2.75 12a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5A.75.75 0 012.75 12zM20.5 12a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5a.75.75 0 01-.75-.75zM5.47 19.78a.75.75 0 011.06 0l.36.35a.75.75 0 11-1.06 1.06l-.36-.35a.75.75 0 010-1.06zM19.62 5.63a.75.75 0 011.06 0l.35.36a.75.75 0 11-1.06 1.06l-.35-.36a.75.75 0 010-1.06z"/>
                            </svg>
                        </span>
                        <span class="dark:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M21.75 14.5A8.25 8.25 0 1110 2.25a.75.75 0 01.7 1.03 6.75 6.75 0 009.02 9.02.75.75 0 011.03.7z"/>
                            </svg>
                        </span>
                    </button>
                    <a class="text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=logout'); ?>">Sair</a>
                </div>
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
                <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=schedules'); ?>">Agendamentos</a>
                <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=invoices'); ?>">Notas Fiscais</a>
                <?php if ($is_admin): ?>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=schedules_create'); ?>">Novo Agendamento</a>
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
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=schedules'); ?>">Agendamentos</a>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=invoices'); ?>">Notas Fiscais</a>
                    <?php if ($is_admin): ?>
                        <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=schedules_create'); ?>">Novo Agendamento</a>
                    <?php endif; ?>
                    <hr class="my-3 border-slate-200">
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=inventory'); ?>">Estoque</a>
                </nav>
            </aside>
            <main class="flex-1 px-4 py-6 md:px-8 md:py-8">
            <script>
                (() => {
                    const root = document.documentElement;
                    const toggle = document.getElementById('themeToggle');
                    const toggleMobile = document.getElementById('themeToggleMobile');
                    const setTheme = (mode) => {
                        if (mode === 'dark') {
                            root.classList.add('dark');
                        } else {
                            root.classList.remove('dark');
                        }
                        localStorage.setItem('theme', mode);
                    };
                    const handleToggle = () => {
                        const isDark = root.classList.contains('dark');
                        setTheme(isDark ? 'light' : 'dark');
                    };
                    if (toggle) toggle.addEventListener('click', handleToggle);
                    if (toggleMobile) toggleMobile.addEventListener('click', handleToggle);
                })();
            </script>
