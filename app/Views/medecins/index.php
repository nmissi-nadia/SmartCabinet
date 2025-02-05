<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1>Liste des Médecins</h1>
    
    <a href="/medecins/create" class="btn btn-primary mb-3">Ajouter un médecin</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Spécialité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($medecins as $medecin): ?>
                <tr>
                    <td><?= htmlspecialchars($medecin['id']) ?></td>
                    <td><?= htmlspecialchars($medecin['nom']) ?></td>
                    <td><?= htmlspecialchars($medecin['prenom']) ?></td>
                    <td><?= htmlspecialchars($medecin['email']) ?></td>
                    <td><?= htmlspecialchars($medecin['telephone']) ?></td>
                    <td><?= htmlspecialchars($medecin['specialite']) ?></td>
                    <td>
                        <a href="/medecins/edit/<?= $medecin['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="/medecins/delete/<?= $medecin['id'] ?>" method="POST" style="display: inline;">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce médecin ?')">
                                Supprimer
                            </button>
                        </form>
                        <a href="/rendez-vous/medecin/<?= $medecin['id'] ?>" class="btn btn-sm btn-info">Rendez-vous</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
