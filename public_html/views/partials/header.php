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
        <header class="fixed top-0 left-0 right-0 z-20 bg-white/80 backdrop-blur border-b border-slate-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-brand-500 text-white grid place-items-center font-semibold">C</div>
                    <div>
                        <p class="text-lg font-semibold leading-tight"><?php echo htmlspecialchars($config['company']['name']); ?></p>
                        <p class="text-xs text-slate-500">CRM de Orcamentos</p>
                    </div>
                </div>
                <div class="text-sm text-slate-600">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>
                    <a class="ml-4 text-brand-700 hover:text-brand-900" href="<?php echo base_url('index.php?action=logout'); ?>">Sair</a>
                </div>
            </div>
        </header>
        <div class="flex pt-20">
            <aside class="w-64 min-h-screen border-r border-slate-200 bg-white/70 px-4 py-6">
                <nav class="space-y-2 text-sm">
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php'); ?>">Dashboard</a>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=clients'); ?>">Clientes</a>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=products'); ?>">Produtos</a>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=quotes'); ?>">Orcamentos</a>
                    <a class="block rounded-lg px-4 py-2 text-slate-700 hover:bg-brand-50" href="<?php echo base_url('index.php?action=quotes_create'); ?>">Novo Orcamento</a>
                </nav>
            </aside>
            <main class="flex-1 px-8 py-8">
