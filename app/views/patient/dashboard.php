<?php
$baseUrl = \App\Core\Application::$app->getBaseUrl();
$title = "Tableau de bord";
$currentPage = 'dashboard';
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Informations du patient -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Informations personnelles
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Vos informations de profil
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Nom complet
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($user->prenom . ' ' . $user->nom) ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Email
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($user->email) ?>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Numéro de sécurité sociale
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($patient->numero_secu) ?>
                </dd>
            </div>
        </dl>
    </div>
</div>

<!-- Liste des rendez-vous -->
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
        <a href="<?= $baseUrl ?>/rendez-vous/create" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Nouveau rendez-vous
        </a>
    </div>
    
    <?php if (empty($rendezVous)): ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rendez-vous</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau rendez-vous.</p>
            <div class="mt-6">
                <a href="<?= $baseUrl ?>/rendez-vous/create" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Prendre un rendez-vous
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white shadow overflow-hidden">
            <ul class="divide-y divide-gray-200">
                <?php foreach ($rendezVous as $rdv): ?>
                    <li class="p-4 hover:bg-gray-50">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    Dr. <?= htmlspecialchars($rdv->medecin_nom) ?> <?= htmlspecialchars($rdv->prenom) ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <?= htmlspecialchars($rdv->specialite) ?>
                                </p>
                                <p class="text-sm text-gray-900">
                                    <?= $rdv->date_rdv ? date('d/m/Y H:i', strtotime($rdv->date_rdv)) : 'Date non définie' ?>
                                </p>
                                <?php if ($rdv->commentaire): ?>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <?= htmlspecialchars($rdv->commentaire) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="inline-flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php
                                    switch($rdv->statut) {
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
                                    <?= htmlspecialchars($rdv->statut) ?>
                                </span>
                            </div>
                            <?php if ($rdv->statut !== 'Annulé'): ?>
                                <form action="<?= $baseUrl ?>/rendezvous/cancel" method="POST" class="inline">
                                    <input type="hidden" name="id_rdv" value="<?= $rdv->id_rdv ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                        Annuler
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
