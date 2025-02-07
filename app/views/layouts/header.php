<?php
$baseUrl = \App\Core\Application::$app->getBaseUrl();
?>
<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SmartCabinet' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            background-attachment: fixed;
        }
        .page-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin: 2rem auto;
            max-width: 1200px;
            padding: 2rem;
        }
        .nav-link {
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full">
        <nav class="bg-gradient-to-r from-blue-600 to-blue-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-white text-xl font-bold">SmartCabinet</h1>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <?php if (isset($_SESSION['user_role'])): ?>
                                    <?php if ($_SESSION['user_role'] === 'Patient'): ?>
                                        <a href="<?= $baseUrl ?>/patient/dashboard" 
                                           class="nav-link <?= $currentPage === 'dashboard' ? 'bg-blue-700 text-white' : 'text-white hover:bg-blue-700' ?> rounded-md px-3 py-2 text-sm font-medium">
                                            Tableau de bord
                                        </a>
                                        <a href="<?= $baseUrl ?>/patient/profile" 
                                           class="nav-link <?= $currentPage === 'profile' ? 'bg-blue-700 text-white' : 'text-white hover:bg-blue-700' ?> rounded-md px-3 py-2 text-sm font-medium">
                                            Mon profil
                                        </a>
                                    <?php elseif ($_SESSION['user_role'] === 'Medecin'): ?>
                                        <a href="<?= $baseUrl ?>/medecin/dashboard" 
                                           class="nav-link <?= $currentPage === 'dashboard' ? 'bg-blue-700 text-white' : 'text-white hover:bg-blue-700' ?> rounded-md px-3 py-2 text-sm font-medium">
                                            Tableau de bord
                                        </a>
                                        <a href="<?= $baseUrl ?>/medecin/profile" 
                                           class="nav-link <?= $currentPage === 'profile' ? 'bg-blue-700 text-white' : 'text-white hover:bg-blue-700' ?> rounded-md px-3 py-2 text-sm font-medium">
                                            Mon profil
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <span class="text-white mr-4">
                                    <?= $_SESSION['user_name'] ?? '' ?>
                                </span>
                                <a href="<?= $baseUrl ?>/auth/logout" 
                                   class="nav-link text-white hover:bg-blue-700 rounded-md px-3 py-2 text-sm font-medium">
                                    Déconnexion
                                </a>
                            <?php else: ?>
                                <a href="<?= $baseUrl ?>/auth/login" 
                                   class="nav-link text-white hover:bg-blue-700 rounded-md px-3 py-2 text-sm font-medium">
                                    Connexion
                                </a>
                                <a href="<?= $baseUrl ?>/auth/register" 
                                   class="nav-link text-white hover:bg-blue-700 rounded-md px-3 py-2 text-sm font-medium ml-2">
                                    Inscription
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <div class="page-container">
                    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
