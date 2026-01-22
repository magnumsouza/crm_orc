            </main>
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
    </script>
</body>
</html>
