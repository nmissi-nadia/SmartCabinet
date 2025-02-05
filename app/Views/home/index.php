<?php $title = 'Accueil'; $currentPage = 'home'; ?>

<div class="max-w-7xl mx-auto">
    <!-- Hero Section -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-12 mb-16">
        <div class="md:w-1/2">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Bienvenue sur SmartCabinet
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Votre plateforme de gestion de cabinet médical intelligente et intuitive. Prenez rendez-vous en ligne, consultez votre historique médical et communiquez facilement avec votre médecin.
            </p>
            <?php if (!isset($_SESSION['user'])): ?>
            <div class="flex gap-4">
                <a href="/login" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Se connecter
                </a>
                <a href="/register" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 border-blue-600">
                    S'inscrire
                </a>
            </div>
            <?php endif; ?>
        </div>
        <div class="md:w-1/2">
            <div class="bg-white rounded-lg shadow-xl p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Nos services</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Service 1 -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Rendez-vous en ligne</h3>
                            <p class="mt-2 text-sm text-gray-500">Prenez rendez-vous 24h/24 et 7j/7</p>
                        </div>
                    </div>

                    <!-- Service 2 -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Dossier médical</h3>
                            <p class="mt-2 text-sm text-gray-500">Accédez à votre historique médical</p>
                        </div>
                    </div>

                    <!-- Service 3 -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Messagerie sécurisée</h3>
                            <p class="mt-2 text-sm text-gray-500">Communiquez avec votre médecin</p>
                        </div>
                    </div>

                    <!-- Service 4 -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Rappels</h3>
                            <p class="mt-2 text-sm text-gray-500">Notifications de vos rendez-vous</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <!-- Horaires -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Horaires d'ouverture
            </h3>
            <ul class="space-y-2 text-gray-600">
                <li>Lundi - Vendredi : 9h00 - 18h00</li>
                <li>Samedi : 9h00 - 12h00</li>
                <li>Dimanche : Fermé</li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Nous trouver
            </h3>
            <address class="not-italic text-gray-600">
                <strong>Cabinet Médical</strong><br>
                123 rue de la Santé<br>
                75000 Paris<br>
                <a href="tel:+33123456789" class="text-blue-600 hover:text-blue-800">01 23 45 67 89</a><br>
                <a href="mailto:contact@smartcabinet.fr" class="text-blue-600 hover:text-blue-800">contact@smartcabinet.fr</a>
            </address>
        </div>

        <!-- Urgences -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Urgences
            </h3>
            <p class="text-gray-600 mb-2">En cas d'urgence en dehors des heures d'ouverture :</p>
            <ul class="space-y-2 text-gray-600">
                <li><strong>SAMU :</strong> 15</li>
                <li><strong>Pompiers :</strong> 18</li>
                <li><strong>Numéro d'urgence européen :</strong> 112</li>
            </ul>
        </div>
    </div>
</div>
