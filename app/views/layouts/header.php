<?php
$baseUrl = \App\Core\Application::$app->getBaseUrl();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>SmartCabinet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?= $baseUrl ?>" class="text-xl font-bold text-blue-600">SmartCabinet</a>
                    </div>
                    <?php if (isset($_SESSION['user_role'])): ?>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <?php if ($_SESSION['user_role'] === 'Patient'): ?>
                                <a href="<?= $baseUrl ?>/patient/dashboard" class="<?= $currentPage === 'dashboard' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Tableau de bord
                                </a>
                                <a href="<?= $baseUrl ?>/rendez-vous/create" class="<?= $currentPage === 'create_rdv' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Nouveau rendez-vous
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </span>
                        <a href="<?= $baseUrl ?>/auth/logout" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            Déconnexion
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
