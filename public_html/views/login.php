<?php $config = require __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM Orcamentos</title>
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
                            100: '#dbe8ff',
                            500: '#4d7dff',
                            700: '#2750d6',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 font-display text-slate-900">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md rounded-2xl bg-white/90 shadow-lg shadow-blue-100/60 p-8 border border-slate-200">
            <div class="mb-6">
                <div class="h-12 w-12 rounded-xl bg-brand-500 text-white grid place-items-center font-semibold">C</div>
                <h1 class="mt-4 text-2xl font-semibold"><?php echo htmlspecialchars($config['company']['name']); ?></h1>
                <p class="text-sm text-slate-500">Acesse sua area de orcamentos</p>
            </div>
            <?php if (!empty($flash)): ?>
                <div class="mb-4 rounded-lg px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
                    <?php echo htmlspecialchars($flash['message']); ?>
                </div>
            <?php endif; ?>
            <form method="post" action="<?php echo base_url('index.php?action=login'); ?>" class="space-y-4">
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-500">Usuario</label>
                    <input type="text" name="username" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-brand-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-500">Senha</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-brand-500 focus:outline-none" required>
                </div>
                <button class="w-full rounded-lg bg-brand-500 px-4 py-2 text-white font-medium hover:bg-brand-700">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
