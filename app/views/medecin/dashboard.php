<?php
$baseUrl = \App\Core\Application::$app->getBaseUrl();
$title = "Tableau de bord";
$currentPage = 'dashboard';
require_once __DIR__ . '/../layouts/header.php';
?>
    <div class="min-h-screen">
        <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <?php if (isset($_GET['error'])): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>
            
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
                                                <a href="<?= $baseUrl ?>/medecin/appointment/confirm/<?= $rdv['id_rdv'] ?>" class="text-green-600 hover:text-green-900 mr-3">Confirmer</a>
                                                <a href="<?= $baseUrl ?>/medecin/appointment/cancel/<?= $rdv['id_rdv'] ?>" class="text-red-600 hover:text-red-900">Annuler</a>
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
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
