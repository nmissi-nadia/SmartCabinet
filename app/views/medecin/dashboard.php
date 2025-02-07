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
            
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total des patients -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total des patients</p>
                            <p class="text-2xl font-semibold text-gray-900"><?= $stats['total_patients'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Consultations du mois -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Consultations ce mois</p>
                            <p class="text-2xl font-semibold text-gray-900"><?= $stats['consultations_mois'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Nouveaux patients -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Nouveaux patients</p>
                            <p class="text-2xl font-semibold text-gray-900"><?= $stats['nouveaux_patients'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Taux de consultation -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Taux de consultation</p>
                            <p class="text-2xl font-semibold text-gray-900"><?= $stats['taux_consultation'] ?>%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition des consultations -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Répartition des consultations par jour</h2>
                <div class="relative">
                    <div class="flex items-center justify-between space-x-4">
                        <?php
                        $max_consultations = 0;
                        foreach ($stats['repartition_jours'] as $jour) {
                            $max_consultations = max($max_consultations, $jour['nombre']);
                        }
                        foreach ($stats['repartition_jours'] as $jour): 
                            $hauteur = $max_consultations > 0 ? ($jour['nombre'] / $max_consultations) * 100 : 0;
                        ?>
                            <div class="flex flex-col items-center">
                                <div class="relative w-4 bg-blue-100 rounded-t">
                                    <div class="absolute bottom-0 w-full bg-blue-500 rounded-t" style="height: <?= $hauteur ?>%"></div>
                                </div>
                                <span class="mt-2 text-xs font-medium text-gray-500"><?= substr($jour['jour'], 0, 3) ?></span>
                                <span class="text-xs font-semibold text-gray-700"><?= $jour['nombre'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($rdv['patient_nom']) ?> <?= htmlspecialchars($rdv['patient_prenom']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php
                                                switch($rdv['statut']) {
                                                    case 'Confirmé':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'En attente':
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'Annulé':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= htmlspecialchars($rdv['statut']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if ($rdv['statut'] === 'En attente'): ?>
                                                <a href="<?= $baseUrl ?>/medecin/appointment/confirm/<?= $rdv['id_rdv'] ?>" 
                                                   class="text-green-600 hover:text-green-900 mr-3">Confirmer</a>
                                                <a href="<?= $baseUrl ?>/medecin/appointment/cancel/<?= $rdv['id_rdv'] ?>" 
                                                   class="text-red-600 hover:text-red-900">Annuler</a>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($rdv['patient_nom']) ?> <?= htmlspecialchars($rdv['patient_prenom']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php
                                                switch($rdv['statut']) {
                                                    case 'Confirmé':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'En attente':
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'Annulé':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= htmlspecialchars($rdv['statut']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if ($rdv['statut'] === 'En attente'): ?>
                                                <a href="<?= $baseUrl ?>/medecin/appointment/confirm/<?= $rdv['id_rdv'] ?>" 
                                                   class="text-green-600 hover:text-green-900 mr-3">Confirmer</a>
                                                <a href="<?= $baseUrl ?>/medecin/appointment/cancel/<?= $rdv['id_rdv'] ?>" 
                                                   class="text-red-600 hover:text-red-900">Annuler</a>
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
