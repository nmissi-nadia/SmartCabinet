<?php
$baseUrl = \App\Core\Application::$app->getBaseUrl();
?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Mes Rendez-vous
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Liste de tous vos rendez-vous
            </p>
        </div>
        <a href="<?= $baseUrl ?>/appointments/new" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Nouveau rendez-vous
        </a>
    </div>
    
    <?php if (empty($appointments)): ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rendez-vous</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau rendez-vous.</p>
            <div class="mt-6">
                <a href="<?= $baseUrl ?>/appointments/new" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Prendre rendez-vous
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <ul class="divide-y divide-gray-200">
                <?php foreach ($appointments as $appointment): 
                    $medecin = $appointment->getMedecin();
                    $user = $medecin->getUser();
                ?>
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        Dr. <?= htmlspecialchars($user->nom . ' ' . $user->prenom) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= htmlspecialchars($medecin->specialite) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <div class="text-sm text-gray-900">
                                    <?= (new DateTime($appointment->date_heure))->format('d/m/Y H:i') ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= $appointment->status ?>
                                </div>
                            </div>
                            <div class="ml-4">
                                <a href="<?= $baseUrl ?>/appointments/<?= $appointment->id_rendez_vous ?>" class="text-blue-600 hover:text-blue-900">
                                    Détails
                                </a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
