<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée | Cabinet Médical</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
    <main class="min-h-screen flex items-center justify-center p-4 sm:p-8">
        <div class="text-center">
            <!-- Icon -->
            <div class="flex justify-center mb-8">
                <svg class="w-32 h-32 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                        <animate attributeName="d" 
                            dur="3s" 
                            repeatCount="indefinite"
                            values="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z;
                                M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 1-4.5 2.5-1.5-1.5-2.74-2.5-4.5-2.5A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z;
                                M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </div>

            <!-- Error Message -->
            <div class="space-y-4">
                <h1 class="text-6xl font-bold text-blue-600">404</h1>
                <h2 class="text-3xl font-semibold text-gray-900">Page non trouvée</h2>
                <p class="text-gray-600 max-w-lg mx-auto">
                    Désolé, nous ne trouvons pas la page que vous recherchez. Elle a peut-être été déplacée ou supprimée.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 space-y-4 sm:space-y-0 sm:space-x-4">
                <button onclick="window.history.back()" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </button>
                <a href="/" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Accueil
                </a>
            </div>

            <!-- Helpful Links -->
            <div class="mt-12">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pages qui pourraient vous intéresser :</h3>
                <div class="grid gap-4 max-w-lg mx-auto text-left">
                    <a href="/rendez-vous" class="p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 flex items-center">
                        <span class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <div>
                            <div class="font-medium text-gray-900">Prise de rendez-vous</div>
                            <div class="text-sm text-gray-500">Planifiez votre prochaine consultation</div>
                        </div>
                    </a>
                    <a href="/contact" class="p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 flex items-center">
                        <span class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <div>
                            <div class="font-medium text-gray-900">Contact</div>
                            <div class="text-sm text-gray-500">Nous sommes là pour vous aider</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Animation du battement de cœur pour l'icône
        const heartbeat = document.querySelector('svg path');
        let scale = 1;

        function animate() {
            scale = scale === 1 ? 1.1 : 1;
            heartbeat.style.transform = `scale(${scale})`;
            heartbeat.style.transformOrigin = 'center';
            requestAnimationFrame(animate);
        }

        animate();
    </script>
</body>
</html>