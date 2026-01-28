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
                            50: '#f2f7ff',
                            100: '#dbe8ff',
                            300: '#8fb4ff',
                            500: '#4d7dff',
                            700: '#2750d6',
                            900: '#162a7a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-slate-950 font-display text-slate-900">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute -left-20 top-10 h-64 w-64 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="pointer-events-none absolute right-0 top-40 h-72 w-72 rounded-full bg-emerald-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 left-1/3 h-80 w-80 rounded-full bg-sky-300/30 blur-3xl"></div>

        <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-6xl items-center px-6 py-10">
            <div class="grid w-full items-stretch gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <section class="hidden flex-col justify-between rounded-3xl bg-gradient-to-br from-brand-500 via-brand-700 to-slate-900 p-10 text-white shadow-2xl lg:flex">
                    <div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 text-lg font-semibold">
                            <svg width="40" height="26" viewBox="0 0 320 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                              <g transform="translate(10,10)">
                                <rect x="0" y="20" width="50" height="40" rx="6" fill="#1E88E5"/>
                                <circle cx="25" cy="40" r="10" fill="#43A047"/>
                                <g stroke="#43A047" stroke-width="3">
                                  <line x1="25" y1="20" x2="25" y2="10"/>
                                  <line x1="25" y1="60" x2="25" y2="70"/>
                                  <line x1="5" y1="40" x2="-5" y2="40"/>
                                  <line x1="45" y1="40" x2="55" y2="40"/>
                                </g>
                              </g>
                              <text x="80" y="55" font-family="Segoe UI, Arial, sans-serif" font-size="28" fill="#1E88E5" font-weight="600">WebService</text>
                              <text x="82" y="78" font-family="Segoe UI, Arial, sans-serif" font-size="14" fill="#555">Orçamentos • Estoque • Serviços</text>
                            </svg>
                        </div>
                        <h1 class="mt-6 text-3xl font-semibold leading-tight"><?php echo htmlspecialchars($config['company']['name']); ?></h1>
                        <p class="mt-2 text-sm text-white/70">CRM moderno para orçamentos e agenda inteligente.</p>
                    </div>
                    <div class="space-y-4 text-sm text-white/80">
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-xs uppercase tracking-wider text-white/60">Insights</p>
                            <p class="mt-2 text-lg font-semibold" id="loginInsight">Controle total do estoque e do faturamento.</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-xs uppercase tracking-wider text-white/60">Fluxo</p>
                            <p class="mt-2 text-lg font-semibold" id="loginFlow">Orçamentos, agendamentos e notas em um só lugar.</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl bg-white/90 p-8 shadow-2xl ring-1 ring-white/20 backdrop-blur">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                            <svg width="40" height="26" viewBox="0 0 320 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                              <g transform="translate(10,10)">
                                <rect x="0" y="20" width="50" height="40" rx="6" fill="#1E88E5"/>
                                <circle cx="25" cy="40" r="10" fill="#43A047"/>
                                <g stroke="#43A047" stroke-width="3">
                                  <line x1="25" y1="20" x2="25" y2="10"/>
                                  <line x1="25" y1="60" x2="25" y2="70"/>
                                  <line x1="5" y1="40" x2="-5" y2="40"/>
                                  <line x1="45" y1="40" x2="55" y2="40"/>
                                </g>
                              </g>
                              <text x="80" y="55" font-family="Segoe UI, Arial, sans-serif" font-size="28" fill="#1E88E5" font-weight="600">WebService</text>
                              <text x="82" y="78" font-family="Segoe UI, Arial, sans-serif" font-size="14" fill="#555">Orçamentos • Estoque • Serviços</text>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($config['company']['name']); ?></h2>
                            <p class="text-sm text-slate-500">Acesse sua área de orçamentos</p>
                        </div>
                    </div>

                    <?php if (!empty($flash)): ?>
                        <div class="mt-6 rounded-xl px-4 py-3 text-sm <?php echo $flash['type'] === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'; ?>">
                            <?php echo htmlspecialchars($flash['message']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo base_url('index.php?action=login'); ?>" class="mt-6 space-y-4">
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Usuario</label>
                            <input type="text" name="username" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" required>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-wide text-slate-500">Senha</label>
                            <input type="password" name="password" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" required>
                        </div>
                        <button class="w-full rounded-xl bg-gradient-to-r from-brand-500 to-brand-700 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 transition hover:-translate-y-0.5">Entrar</button>
                    </form>

                    <div class="mt-6 flex items-center gap-3 text-xs uppercase tracking-wider text-slate-400">
                        <span class="h-px flex-1 bg-slate-200"></span>
                        Ou continue com
                        <span class="h-px flex-1 bg-slate-200"></span>
                    </div>

                    <a href="<?php echo htmlspecialchars($google_login_url); ?>" class="mt-6 w-full flex items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-slate-700 font-medium shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2">
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
                </section>
            </div>
        </div>
    </div>
    <script>
        (() => {
            const insights = [
                'Controle total do estoque e do faturamento.',
                'Visão rápida de receitas e custos por período.',
                'Alertas automáticos de estoque baixo.',
                'Histórico completo de clientes e orçamentos.',
            ];
            const flows = [
                'Orçamentos, agendamentos e notas em um só lugar.',
                'Do orçamento à nota fiscal em poucos cliques.',
                'Agendamento inteligente com slots automáticos.',
                'Fluxo integrado com WhatsApp e PDFs.',
            ];
            const insightEl = document.getElementById('loginInsight');
            const flowEl = document.getElementById('loginFlow');
            if (!insightEl || !flowEl) return;

            let i = 0;
            let f = 0;
            const rotate = () => {
                i = (i + 1) % insights.length;
                f = (f + 1) % flows.length;
                insightEl.classList.add('opacity-0', 'translate-y-1');
                flowEl.classList.add('opacity-0', 'translate-y-1');
                setTimeout(() => {
                    insightEl.textContent = insights[i];
                    flowEl.textContent = flows[f];
                    insightEl.classList.remove('opacity-0', 'translate-y-1');
                    flowEl.classList.remove('opacity-0', 'translate-y-1');
                }, 250);
            };

            insightEl.classList.add('transition', 'duration-300');
            flowEl.classList.add('transition', 'duration-300');
            setInterval(rotate, 3500);
        })();
    </script>
</body>
</html>
