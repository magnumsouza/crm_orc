            </main>
        </div>
    </div>
    <div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-confirm-close></div>
        <div class="modal-panel relative w-full max-w-md mx-4 rounded-2xl bg-white shadow-xl border border-slate-200">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold" id="confirm-title">Confirmar</h2>
                    <p class="text-sm text-slate-500" id="confirm-message">Tem certeza?</p>
                </div>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-confirm-close>Fechar</button>
            </div>
            <div class="flex flex-wrap gap-3 justify-end px-6 py-4">
                <button type="button" class="btn-outline px-4 py-2 text-sm font-semibold transition" data-confirm-close>Cancelar</button>
                <a href="#" class="btn-primary px-4 py-2 text-sm font-semibold transition" id="confirm-action">Confirmar</a>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var button = document.getElementById('mobileMenuButton');
            var menu = document.getElementById('mobileMenu');
            var overlay = document.getElementById('mobileMenuOverlay');

            if (!button || !menu || !overlay) {
                return;
            }

            function openMenu() {
                menu.classList.remove('hidden');
                overlay.classList.remove('hidden');
                menu.classList.add('translate-x-0');
                menu.classList.remove('translate-x-[-100%]');
                button.setAttribute('aria-expanded', 'true');
                document.body.classList.add('overflow-hidden');
            }

            function closeMenu() {
                menu.classList.add('translate-x-[-100%]');
                menu.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('overflow-hidden');
                window.setTimeout(function () {
                    if (menu.classList.contains('translate-x-[-100%]')) {
                        menu.classList.add('hidden');
                    }
                }, 200);
            }

            button.addEventListener('click', function () {
                if (menu.classList.contains('hidden')) {
                    openMenu();
                } else {
                    closeMenu();
                }
            });

            overlay.addEventListener('click', closeMenu);
            window.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });
        })();
        (function () {
            var modal = document.getElementById('confirm-modal');
            var titleEl = document.getElementById('confirm-title');
            var messageEl = document.getElementById('confirm-message');
            var actionEl = document.getElementById('confirm-action');
            if (!modal || !titleEl || !messageEl || !actionEl) {
                return;
            }

            function openConfirm(options) {
                titleEl.textContent = options.title;
                messageEl.textContent = options.message;
                actionEl.textContent = options.confirmText;
                actionEl.href = options.href;
                actionEl.classList.remove('btn-primary', 'btn-danger');
                actionEl.classList.add(options.variant === 'danger' ? 'btn-danger' : 'btn-primary');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeConfirm() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.addEventListener('click', function (event) {
                var target = event.target;
                var link = target && target.closest ? target.closest('[data-confirm-link]') : null;
                if (!link) {
                    return;
                }
                event.preventDefault();
                openConfirm({
                    title: link.getAttribute('data-confirm-title') || 'Confirmar',
                    message: link.getAttribute('data-confirm-message') || 'Tem certeza?',
                    confirmText: link.getAttribute('data-confirm-text') || 'Confirmar',
                    href: link.getAttribute('href') || '#',
                    variant: link.getAttribute('data-confirm-variant') || 'primary'
                });
            });

            modal.querySelectorAll('[data-confirm-close]').forEach(function (btn) {
                btn.addEventListener('click', closeConfirm);
            });

            window.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeConfirm();
                }
            });
        })();
    </script>
</body>
</html>
