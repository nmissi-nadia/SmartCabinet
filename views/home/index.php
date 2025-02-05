<?php $title = 'Accueil'; ?>

<div class="row align-items-center g-5 py-5">
    <div class="col-lg-6">
        <h1 class="display-4 fw-bold lh-1 mb-3">Bienvenue sur SmartCabinet</h1>
        <p class="lead">Votre plateforme de gestion de cabinet médical intelligente et intuitive. Prenez rendez-vous en ligne, consultez votre historique médical et communiquez facilement avec votre médecin.</p>
        <?php if (!isset($_SESSION['user'])): ?>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="/login" class="btn btn-primary btn-lg px-4 me-md-2">
                <i class="bi bi-box-arrow-in-right"></i> Se connecter
            </a>
            <a href="/register" class="btn btn-outline-primary btn-lg px-4">
                <i class="bi bi-person-plus"></i> S'inscrire
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <h2 class="card-title h4 mb-4">Nos services</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-3 me-3">
                                <i class="bi bi-calendar-check p-2"></i>
                            </div>
                            <div>
                                <h3 class="fs-5">Rendez-vous en ligne</h3>
                                <p class="text-muted small">Prenez rendez-vous 24h/24 et 7j/7</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-3 me-3">
                                <i class="bi bi-file-medical p-2"></i>
                            </div>
                            <div>
                                <h3 class="fs-5">Dossier médical</h3>
                                <p class="text-muted small">Accédez à votre historique médical</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-3 me-3">
                                <i class="bi bi-chat-dots p-2"></i>
                            </div>
                            <div>
                                <h3 class="fs-5">Messagerie sécurisée</h3>
                                <p class="text-muted small">Communiquez avec votre médecin</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-3 me-3">
                                <i class="bi bi-bell p-2"></i>
                            </div>
                            <div>
                                <h3 class="fs-5">Rappels</h3>
                                <p class="text-muted small">Notifications de vos rendez-vous</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 py-5">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="card-title h5">
                    <i class="bi bi-clock text-primary"></i>
                    Horaires d'ouverture
                </h3>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">Lundi - Vendredi : 9h00 - 18h00</li>
                    <li class="mb-2">Samedi : 9h00 - 12h00</li>
                    <li>Dimanche : Fermé</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="card-title h5">
                    <i class="bi bi-geo-alt text-primary"></i>
                    Nous trouver
                </h3>
                <address class="mb-0">
                    <strong>Cabinet Médical</strong><br>
                    123 rue de la Santé<br>
                    75000 Paris<br>
                    <i class="bi bi-telephone"></i> <a href="tel:+33123456789">01 23 45 67 89</a><br>
                    <i class="bi bi-envelope"></i> <a href="mailto:contact@smartcabinet.fr">contact@smartcabinet.fr</a>
                </address>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="card-title h5">
                    <i class="bi bi-shield-check text-primary"></i>
                    Urgences
                </h3>
                <p>En cas d'urgence en dehors des heures d'ouverture :</p>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><strong>SAMU :</strong> 15</li>
                    <li class="mb-2"><strong>Pompiers :</strong> 18</li>
                    <li><strong>Numéro d'urgence européen :</strong> 112</li>
                </ul>
            </div>
        </div>
    </div>
</div>
