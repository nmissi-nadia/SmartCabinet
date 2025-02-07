<?php
use App\Core\Application;

$title = "Inscription";
$baseUrl = Application::$app->getBaseUrl();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - SmartCabinet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Créer un compte
                </h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6" action="<?= $baseUrl ?>/auth/register" method="POST">
                <div class="rounded-md shadow-sm -space-y-px">
                    <!-- Choix du rôle -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vous êtes :</label>
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center">
                                <input id="role-patient" name="role" type="radio" value="2" required 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="role-patient" class="ml-2 block text-sm text-gray-900">Patient</label>
                            </div>
                            <div class="flex items-center">
                                <input id="role-medecin" name="role" type="radio" value="1" required 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="role-medecin" class="ml-2 block text-sm text-gray-900">Médecin</label>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de base -->
                    <div>
                        <label for="nom" class="sr-only">Nom</label>
                        <input id="nom" name="nom" type="text" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Nom">
                    </div>
                    <div>
                        <label for="prenom" class="sr-only">Prénom</label>
                        <input id="prenom" name="prenom" type="text" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Prénom">
                    </div>
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input id="email" name="email" type="email" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Email">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Mot de passe</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Mot de passe">
                    </div>
                </div>

                <!-- Champs pour patient -->
                <div id="patient-fields" style="display: none;" class="space-y-6">
                    <div>
                        <label for="numero_securite_sociale" class="sr-only">Numéro de sécurité sociale</label>
                        <input id="numero_securite_sociale" name="numero_securite_sociale" type="text"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Numéro de sécurité sociale">
                    </div>
                </div>

                <!-- Champs pour médecin -->
                <div id="medecin-fields" style="display: none;" class="space-y-6">
                    <div>
                        <label for="specialite" class="sr-only">Spécialité</label>
                        <input id="specialite" name="specialite" type="text"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Spécialité">
                    </div>
                    <div>
                        <label for="numero_rpps" class="sr-only">Numéro RPPS</label>
                        <input id="numero_rpps" name="numero_rpps" type="text"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Numéro RPPS">
                    </div>
                    <div>
                        <label for="adresse_cabinet" class="sr-only">Adresse du cabinet</label>
                        <input id="adresse_cabinet" name="adresse_cabinet" type="text"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Adresse du cabinet">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        S'inscrire
                    </button>
                </div>
            </form>

            <div class="text-sm text-center">
                <a href="/SmartCabinet/login" class="font-medium text-blue-600 hover:text-blue-500">
                    Déjà inscrit ? Connectez-vous
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const patientFields = document.getElementById('patient-fields');
            const medecinFields = document.getElementById('medecin-fields');
            const rolePatient = document.getElementById('role-patient');
            const roleMedecin = document.getElementById('role-medecin');

            function toggleFields() {
                if (rolePatient.checked) {
                    patientFields.style.display = 'block';
                    medecinFields.style.display = 'none';
                } else if (roleMedecin.checked) {
                    patientFields.style.display = 'none';
                    medecinFields.style.display = 'block';
                }
            }

            rolePatient.addEventListener('change', toggleFields);
            roleMedecin.addEventListener('change', toggleFields);
        });
    </script>
</body>
</html>