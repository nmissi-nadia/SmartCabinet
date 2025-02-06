<div class="container mt-4">
    <h1>Nouveau rendez-vous</h1>
    <form action="/rendez-vous/create" method="POST">
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="mb-3">
            <label for="heure" class="form-label">Heure</label>
            <input type="time" class="form-control" id="heure" name="heure" required>
        </div>
        <!-- Ajoutez d'autres champs nécessaires -->
        <button type="submit" class="btn btn-primary">Créer le rendez-vous</button>
    </form>
</div>