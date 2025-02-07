# Cabinet Médical - Système de Gestion

## 📋 Description
Une application web moderne de gestion de cabinet médical développée en PHP natif, suivant le pattern MVC. Cette application permet aux patients de prendre des rendez-vous en ligne et aux médecins de gérer leurs consultations efficacement.

## 🚀 Fonctionnalités

### Pour les Patients
- Création et gestion de compte patient
- Prise de rendez-vous en ligne
- Consultation de l'historique des rendez-vous
- Gestion du profil et des informations personnelles

### Pour les Médecins
- Tableau de bord personnalisé
- Gestion des rendez-vous
- Suivi des patients
- Planning des consultations

## 🛠 Technologies Utilisées
- PHP 8.0+
- PostgreSQL
- HTML5/CSS3
- Tailwind CSS
- JavaScript

## 📦 Prérequis
- PHP >= 8.0
- PostgreSQL >= 13
- Composer
- Serveur Web (Apache/Nginx)

## ⚙️ Installation

1. Cloner le repository
```bash
git clone https://github.com/votre-username/cabinet-medical.git
cd cabinet-medical
```

2. Installer les dépendances
```bash
composer install
```

3. Configuration de la base de données
- Créer une base de données PostgreSQL
- Copier le fichier `.env.example` vers `.env`
- Modifier les paramètres de connexion dans le fichier `.env`

4. Initialiser la base de données
```bash
php migrations/migrate.php
```

5. Démarrer le serveur de développement
```bash
php -S localhost:8000 -t public
```

## 🏗 Structure du Projet
```
cabinet-medical/
├── app/
│   ├── Controllers/    # Contrôleurs de l'application
│   ├── Core/          # Classes core (Router, Database, etc.)
│   ├── Models/        # Modèles de données
│   └── views/         # Vues de l'application
├── config/            # Fichiers de configuration
├── public/           # Point d'entrée public
└── vendor/           # Dépendances (via Composer)
```

## 🔒 Sécurité
- Protection contre les injections SQL (requêtes préparées)
- Hachage sécurisé des mots de passe
- Protection CSRF
- Validation des entrées utilisateur
- Sessions sécurisées

## 🤝 Contribution
Les contributions sont les bienvenues ! Pour contribuer :

1. Forker le projet
2. Créer une branche pour votre fonctionnalité
```bash
git checkout -b feature/AmazingFeature
```
3. Commiter vos changements
```bash
git commit -m 'Add some AmazingFeature'
```
4. Pousser vers la branche
```bash
git push origin feature/AmazingFeature
```
5. Ouvrir une Pull Request

## 📝 License
Ce projet est sous licence MIT - voir le fichier [LICENSE.md](LICENSE.md) pour plus de détails.

## 👥 Auteurs
- **Votre Nom** - *Développement initial* - [VotreGithub](https://github.com/votre-username)

## 📞 Support
Pour toute question ou suggestion, n'hésitez pas à :
- Ouvrir une issue
- Envoyer un email à : votre-email@example.com

## 🙏 Remerciements
- Merci à tous les contributeurs qui participent à ce projet
- Inspiration du design : [Tailwind UI](https://tailwindui.com/)
