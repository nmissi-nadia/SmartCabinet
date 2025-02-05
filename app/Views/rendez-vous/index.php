<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1>Liste des Rendez-vous</h1>
    
    <a href="/rendez-vous/create" class="btn btn-primary mb-3">Nouveau rendez-vous</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rendezVous as $rdv): ?>
                    <tr>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($rdv['date']))) ?></td>
                        <td><?= htmlspecialchars($rdv['heure']) ?></td>
                        <td><?= htmlspecialchars($rdv['patient_nom'] . ' ' . $rdv['patient_prenom']) ?></td>
                        <td><?= htmlspecialchars($rdv['medecin_nom'] . ' ' . $rdv['medecin_prenom']) ?></td>
                        <td><?= htmlspecialchars($rdv['motif']) ?></td>
                        <td>
                            <span class="badge bg-<?= $rdv['statut'] === 'Confirmé' ? 'success' : 
                                                  ($rdv['statut'] === 'En attente' ? 'warning' : 
                                                  ($rdv['statut'] === 'Annulé' ? 'danger' : 'info')) ?>">
                                <?= htmlspecialchars($rdv['statut']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/rendez-vous/edit/<?= $rdv['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <form action="/rendez-vous/delete/<?= $rdv['id'] ?>" method="POST" style="display: inline;">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                                <?php if ($rdv['statut'] === 'En attente'): ?>
                                    <form action="/rendez-vous/status/<?= $rdv['id'] ?>" method="POST" style="display: inline;">
                                        <input type="hidden" name="statut" value="Confirmé">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Confirmer
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap si nécessaire
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
