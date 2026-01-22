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

            <a href="<?php echo htmlspecialchars($google_login_url); ?>" class="mt-6 w-full flex items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-slate-700 font-medium shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white shadow-inner ring-1 ring-slate-200">
                    <svg class="h-5 w-5" viewBox="0 0 24 24">
                        <path fill="#EA4335" d="M12 11.999v3.69h5.207c-.211 1.273-1.5 3.734-5.207 3.734-3.141 0-5.709-2.599-5.709-5.79 0-3.19 2.568-5.789 5.709-5.789 1.787 0 2.985.78 3.675 1.457l2.512-2.438C16.565 5.33 14.47 4.25 12 4.25c-4.391 0-7.958 3.605-7.958 8.043 0 4.438 3.567 8.043 7.958 8.043 4.603 0 7.647-3.304 7.647-7.956 0-.536-.057-.944-.126-1.38H12z"/>
                        <path fill="#34A853" d="M5.173 9.612l2.998 2.23a3.527 3.527 0 0 1 3.3-2.422c.828 0 1.587.29 2.19.772l2.4-2.438A7.77 7.77 0 0 0 11.47 6.25c-2.836 0-5.277 1.66-6.297 3.362z"/>
                        <path fill="#FBBC05" d="M5.173 14.386c.917 1.776 2.951 3.392 6.297 3.392 1.942 0 3.571-.636 4.761-1.736l-2.327-1.782c-.626.424-1.457.681-2.434.681-1.8 0-3.33-1.21-3.875-2.898l-2.422 1.343z"/>
                        <path fill="#4285F4" d="M19.521 12.999c.07-.43.109-.877.109-1.35 0-.392-.046-.773-.126-1.15H12v2.5h4.453c-.204.665-.617 1.23-1.2 1.662l.002-.001 2.327 1.781c1.34-1.27 1.94-2.898 1.94-4.443z"/>
                    </svg>
                </span>
                <span>Continuar com Google</span>
            </a>
        </div>
    </div>
</body>
</html>
