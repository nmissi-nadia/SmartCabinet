<?php
$baseUrl = \App\Core\Application::$app->getBaseUrl();
$title = "Nouveau Rendez-vous";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - SmartCabinet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Prendre un rendez-vous
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form class="space-y-6" action="<?= $baseUrl ?>/rendezvous/create" method="POST">
                    <div>
                        <label for="id_medecin" class="block text-sm font-medium text-gray-700">
                            Médecin
                        </label>
                        <select id="id_medecin" name="id_medecin" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionnez un médecin</option>
                            <?php foreach ($medecins as $medecin): ?>
                                <option value="<?= $medecin['id_medecin'] ?>">
                                    Dr. <?= htmlspecialchars($medecin['nom']) ?> <?= htmlspecialchars($medecin['prenom']) ?> 
                                    - <?= htmlspecialchars($medecin['specialite']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="date_rdv" class="block text-sm font-medium text-gray-700">
                            Date et heure
                        </label>
                        <input type="text" id="date_rdv" name="date_rdv" required
                               class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Sélectionnez une date et heure">
                    </div>

                    <div>
                        <label for="commentaire" class="block text-sm font-medium text-gray-700">
                            Commentaire (optionnel)
                        </label>
                        <textarea id="commentaire" name="commentaire" rows="3"
                                  class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Décrivez brièvement la raison de votre consultation"></textarea>
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Confirmer le rendez-vous
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <a href="<?= $baseUrl ?>/patient/dashboard"
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation du sélecteur de date
            const datePickr = flatpickr("#date_rdv", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                time_24hr: true,
                minuteIncrement: 15,
                disable: [
                    function(date) {
                        // Désactiver les weekends
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ],
                onChange: function(selectedDates, dateStr) {
                    updateDisponibilites(dateStr);
                }
            });

            // Mise à jour des disponibilités en fonction du médecin sélectionné
            document.getElementById('id_medecin').addEventListener('change', function() {
                const dateStr = document.getElementById('date_rdv').value;
                if (dateStr) {
                    updateDisponibilites(dateStr);
                }
            });

            function updateDisponibilites(dateStr) {
                const id_medecin = document.getElementById('id_medecin').value;
                if (!id_medecin) return;

                fetch(`${baseUrl}/rendezvous/disponibilites?id_medecin=${id_medecin}&date=${dateStr}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        // Mettre à jour les créneaux disponibles
                        const disponibilites = data.disponibilites;
                        const creneaux_occupes = new Set(data.creneaux_occupes);

                        // Reconfigurer flatpickr avec les nouvelles disponibilités
                        datePickr.set('enable', [
                            function(date) {
                                const jour = date.toLocaleDateString('fr-FR', { weekday: 'long' }).toLowerCase();
                                if (!disponibilites[jour]) return false;

                                const heure = date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                                return !creneaux_occupes.has(heure);
                            }
                        ]);
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        });
    </script>
</body>
</html>
