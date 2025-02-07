<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-container">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Mon Profil</h1>

                    <?php if ($message): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?= htmlspecialchars($message) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $baseUrl ?>/medecin/profile" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($user->nom) ?>" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($user->prenom) ?>" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user->email) ?>" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="specialite" class="block text-sm font-medium text-gray-700">Spécialité</label>
                                <input type="text" name="specialite" id="specialite" value="<?= htmlspecialchars($medecin->specialite) ?>" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="numero_telephone" class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
                                <input type="tel" name="numero_telephone" id="numero_telephone" value="<?= htmlspecialchars($medecin->numero_telephone) ?>" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="adresse_cabinet" class="block text-sm font-medium text-gray-700">Adresse du cabinet</label>
                                <input type="text" name="adresse_cabinet" id="adresse_cabinet" value="<?= htmlspecialchars($medecin->adresse_cabinet) ?>" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Disponibilités</h3>
                            <div class="space-y-4">
                                <?php
                                $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
                                $disponibilites = $medecin->getDisponibilite();
                                foreach ($jours as $jour):
                                    $plageHoraire = $disponibilites[$jour] ?? ['09:00', '17:00'];
                                ?>
                                    <div class="flex items-center space-x-4">
                                        <label class="w-24 text-sm font-medium text-gray-700 capitalize"><?= $jour ?></label>
                                        <input type="time" name="disponibilites[<?= $jour ?>][]" value="<?= $plageHoraire[0] ?>" 
                                            class="border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <span class="text-gray-500">à</span>
                                        <input type="time" name="disponibilites[<?= $jour ?>][]" value="<?= $plageHoraire[1] ?>" 
                                            class="border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Mettre à jour le profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
