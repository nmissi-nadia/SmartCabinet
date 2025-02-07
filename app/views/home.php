<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-blue-600">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1505751172876-fa1923c5c528?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Medical background">
            <div class="absolute inset-0 bg-blue-600 mix-blend-multiply"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Cabinet Médical</h1>
            <p class="mt-6 text-xl text-blue-100 max-w-3xl">
                Prenez rendez-vous en ligne avec nos médecins qualifiés. Un suivi médical professionnel et personnalisé.
            </p>
            <div class="mt-10">
                <a href="/SmartCabinet/register" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                    Prendre rendez-vous
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Nos Services</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Une prise en charge complète
                </p>
            </div>

            <div class="mt-10">
                <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                            <!-- Hero icon -->
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Consultation en ligne</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Prenez rendez-vous facilement avec nos médecins qualifiés.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Suivi personnalisé</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Un suivi médical adapté à vos besoins spécifiques.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctors Section -->
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Nos Médecins</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Une équipe de professionnels à votre service
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($medecins as $medecin): 
                        $user = $medecin->getUser();
                    ?>
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Dr. <?= htmlspecialchars($user->nom . ' ' . $user->prenom) ?>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                <?= htmlspecialchars($medecin->specialite) ?>
                            </p>
                            <p class="mt-3 text-sm text-gray-500">
                                <?= htmlspecialchars($medecin->adresse_cabinet) ?>
                            </p>
                            <div class="mt-4">
                                <a href="/SmartCabinet/rendez-vous/nouveau?medecin=<?= $medecin->id_medecin ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Prendre rendez-vous
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Prêt à prendre soin de votre santé ?</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-blue-100">
                Inscrivez-vous dès maintenant pour accéder à tous nos services.
            </p>
            <a href="/SmartCabinet/register" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 sm:w-auto">
                Créer un compte
            </a>
        </div>
    </div>
</div>
