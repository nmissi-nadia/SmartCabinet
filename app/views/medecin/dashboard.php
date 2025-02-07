<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médecin - SmartCabinet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-gray-800">SmartCabinet</h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">Dr. <?= htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) ?></span>
                        <a href="/SmartCabinet/auth/logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Déconnexion
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- En-tête du dashboard -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Informations du cabinet</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Spécialité: <span class="font-semibold"><?= htmlspecialchars($medecin['specialite']) ?></span></p>
                        <p class="text-gray-600">Adresse: <span class="font-semibold"><?= htmlspecialchars($medecin['adresse_cabinet']) ?></span></p>
                        <p class="text-gray-600">Téléphone: <span class="font-semibold"><?= htmlspecialchars($medecin['numero_telephone']) ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Rendez-vous du jour -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Rendez-vous du jour</h2>
                <?php if (empty($rendez_vous_jour)): ?>
                    <p class="text-gray-600">Aucun rendez-vous aujourd'hui</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($rendez_vous_jour as $rdv): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('H:i', strtotime($rdv['date_rdv'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($rdv['patient_prenom'] . ' ' . $rdv['patient_nom']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= $rdv['statut'] === 'Confirmé' ? 'bg-green-100 text-green-800' : 
                                                   ($rdv['statut'] === 'En attente' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-red-100 text-red-800') ?>">
                                                <?= htmlspecialchars($rdv['statut']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-indigo-600 hover:text-indigo-900 mr-3">Voir détails</button>
                                            <?php if ($rdv['statut'] === 'En attente'): ?>
                                                <button class="text-green-600 hover:text-green-900 mr-3">Confirmer</button>
                                                <button class="text-red-600 hover:text-red-900">Annuler</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Prochains rendez-vous -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Prochains rendez-vous</h2>
                <?php if (empty($prochains_rdv)): ?>
                    <p class="text-gray-600">Aucun rendez-vous à venir</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($prochains_rdv as $rdv): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('H:i', strtotime($rdv['date_rdv'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($rdv['patient_prenom'] . ' ' . $rdv['patient_nom']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= $rdv['statut'] === 'Confirmé' ? 'bg-green-100 text-green-800' : 
                                                   ($rdv['statut'] === 'En attente' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-red-100 text-red-800') ?>">
                                                <?= htmlspecialchars($rdv['statut']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-indigo-600 hover:text-indigo-900 mr-3">Voir détails</button>
                                            <?php if ($rdv['statut'] === 'En attente'): ?>
                                                <button class="text-green-600 hover:text-green-900 mr-3">Confirmer</button>
                                                <button class="text-red-600 hover:text-red-900">Annuler</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
