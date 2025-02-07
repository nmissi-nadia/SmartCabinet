<?php
$baseUrl = \App\Core\Application::$app->getBaseUrl();
$user = $patient->getUser();
?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Mon Profil
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Informations personnelles et médicales
        </p>
    </div>
    
    <form method="POST" class="border-t border-gray-200">
        <dl>
            <!-- Informations personnelles -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Nom complet
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($user->nom . ' ' . $user->prenom) ?>
                </dd>
            </div>
            
            <!-- Numéro de sécurité sociale -->
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Numéro de sécurité sociale
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($patient->numero_securite_sociale) ?>
                </dd>
            </div>
            
            <!-- Groupe sanguin -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Groupe sanguin
                </dt>
                <dd class="mt-1 sm:mt-0 sm:col-span-2">
                    <select name="groupe_sanguin" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Non spécifié</option>
                        <?php
                        $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                        foreach ($groupes as $groupe): ?>
                            <option value="<?= $groupe ?>" <?= $patient->groupe_sanguin === $groupe ? 'selected' : '' ?>>
                                <?= $groupe ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </dd>
            </div>
            
            <!-- Allergies -->
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Allergies
                </dt>
                <dd class="mt-1 sm:mt-0 sm:col-span-2">
                    <textarea name="allergies" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"><?= htmlspecialchars($patient->allergies ?? '') ?></textarea>
                </dd>
            </div>
            
            <!-- Antécédents médicaux -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Antécédents médicaux
                </dt>
                <dd class="mt-1 sm:mt-0 sm:col-span-2">
                    <textarea name="antecedents_medicaux" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"><?= htmlspecialchars($patient->antecedents_medicaux ?? '') ?></textarea>
                </dd>
            </div>
            
            <!-- Boutons -->
            <div class="bg-white px-4 py-5 sm:px-6 flex justify-end space-x-3">
                <button type="button" onclick="window.location.href='<?= $baseUrl ?>/patient/dashboard'" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Enregistrer
                </button>
            </div>
        </dl>
    </form>
</div>
