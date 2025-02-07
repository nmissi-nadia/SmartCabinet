<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Cabinet Médical' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?= \App\Core\Application::$app->getBaseUrl() ?>/" class="text-xl font-bold text-blue-600">
                            Cabinet Médical
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <?php if (\App\Core\Application::isGuest()): ?>
                        <a href="<?= \App\Core\Application::$app->getBaseUrl() ?>/auth/login" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Se connecter
                        </a>
                        <a href="<?= \App\Core\Application::$app->getBaseUrl() ?>/auth/register" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            S'inscrire
                        </a>
                    <?php else: ?>
                        <?php if (\App\Core\Application::isPatient()): ?>
                            <a href="<?= \App\Core\Application::$app->getBaseUrl() ?>/patient/dashboard" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Mon espace
                            </a>
                        <?php endif; ?>
                        <a href="<?= \App\Core\Application::$app->getBaseUrl() ?>/auth/logout" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Déconnexion
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <?php if (isset($flash) && $flash): ?>
            <div class="mb-4 px-4 py-3 rounded relative <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>" role="alert">
                <span class="block sm:inline"><?= $flash['message'] ?></span>
            </div>
        <?php endif; ?>

        {{content}}
    </main>

    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; <?= date('Y') ?> Cabinet Médical. Tous droits réservés.
            </p>
        </div>
    </footer>
</body>
</html>
