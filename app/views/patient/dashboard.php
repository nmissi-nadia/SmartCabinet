<?php
$baseUrl = \App\Core\Application::$app->getBaseUrl();
?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Tableau de bord Patient
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Bienvenue dans votre espace personnel
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <!-- Prochains rendez-vous -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Prochains rendez-vous
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?php if (empty($appointments)): ?>
                        <p class="text-gray-500">Aucun rendez-vous prévu</p>
                    <?php else: ?>
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($appointments as $appointment): ?>
                                <li class="py-3">
                                    <div class="flex justify-between">
                                        <div>
                                            <p class="font-medium">Dr. <?= htmlspecialchars($appointment->getMedecin()->getNomComplet()) ?></p>
                                            <p class="text-gray-500"><?= htmlspecialchars($appointment->date_heure) ?></p>
                                        </div>
                                        <a href="<?= $baseUrl ?>/appointments/<?= $appointment->id_rendez_vous ?>" class="text-blue-600 hover:text-blue-800">
                                            Détails
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </dd>
            </div>
            
            <!-- Liens rapides -->
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Actions rapides
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <a href="<?= $baseUrl ?>/appointments/new" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Nouveau rendez-vous
                        </a>
                        <a href="<?= $baseUrl ?>/patient/profile" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Modifier mon profil
                        </a>
                    </div>
                </dd>
            </div>
        </dl>
    </div>
</div>
