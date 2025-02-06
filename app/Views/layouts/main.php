    <?php require_once __DIR__ . '/../layouts/header.php'; ?>

    <!-- Main content -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($flash)): ?>
        <div class="mb-4 px-4 py-3 rounded relative <?= $flash['type'] === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' ?>" role="alert">
            <span class="block sm:inline"><?= $flash['message'] ?></span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
        <?php endif; ?>
        
        <!-- <?= $content ?> -->
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-md mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="text-gray-500 text-sm">
                    &copy; <?= date('Y') ?> SmartCabinet. Tous droits réservés.
                </div>
                <div class="space-x-4">
                    <a href="/contact" class="text-gray-500 hover:text-gray-700 text-sm">Contact</a>
                    <a href="/mentions-legales" class="text-gray-500 hover:text-gray-700 text-sm">Mentions légales</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Toggle user menu
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = userMenuButton?.nextElementSibling;
        
        userMenuButton?.addEventListener('click', () => {
            userMenu.classList.toggle('hidden');
        });

        // Close alerts
        document.querySelectorAll('[role="alert"]').forEach(alert => {
            const closeButton = alert.querySelector('svg[role="button"]');
            closeButton?.addEventListener('click', () => {
                alert.remove();
            });
        });
    </script>
</body>
</html>
