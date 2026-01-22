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

            <div class="mt-6 relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-2 text-slate-500">Ou continue com</span>
                </div>
            </div>

            <a href="<?php echo htmlspecialchars($google_login_url); ?>" class="mt-6 w-full flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-slate-700 font-medium hover:bg-slate-50 transition">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Google
            </a>
        </div>
    </div>
</body>
</html>
