<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1>Nouveau Médecin</h1>

    <form action="/medecins/store" method="POST" class="needs-validation" novalidate>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
                <div class="invalid-feedback">
                    Le nom est requis
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
                <div class="invalid-feedback">
                    Le prénom est requis
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">
                    Veuillez fournir un email valide
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                <div class="invalid-feedback">
                    Le numéro de téléphone est requis
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="specialite" class="form-label">Spécialité</label>
            <select class="form-select" id="specialite" name="specialite" required>
                <option value="">Choisir une spécialité...</option>
                <option value="Généraliste">Généraliste</option>
                <option value="Cardiologue">Cardiologue</option>
                <option value="Dermatologue">Dermatologue</option>
                <option value="Pédiatre">Pédiatre</option>
                <option value="Psychiatre">Psychiatre</option>
                <option value="Ophtalmologue">Ophtalmologue</option>
            </select>
            <div class="invalid-feedback">
                Veuillez choisir une spécialité
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/medecins" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
// Validation des formulaires Bootstrap
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
