-- Création de la base de données
CREATE DATABASE cabinet_medical;

-- Connexion à la base de données
\c cabinet_medical;

-- Création de la table des rôles
CREATE TABLE roles (
    id_role SERIAL PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    CONSTRAINT chk_role_name CHECK (role_name IN ('Médecin', 'Patient'))
);

-- Création de la table des utilisateurs
CREATE TABLE utilisateurs (
    id_utilisateur SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe TEXT NOT NULL,
    id_role INT REFERENCES roles(id_role) ON DELETE SET NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table des informations des médecins
CREATE TABLE infos_medecins (
    id_medecin SERIAL PRIMARY KEY,
    id_utilisateur INT UNIQUE REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE,
    specialite VARCHAR(100) NOT NULL,
    adresse_cabinet VARCHAR(255),
    numero_telephone VARCHAR(20) NOT NULL,
    disponibilite JSONB -- Exemple : {"lundi": ["09:00", "17:00"], "mardi": ["09:00", "15:00"]}
);

-- Création de la table des informations des patients
CREATE TABLE infos_patients (
    id_patient SERIAL PRIMARY KEY,
    id_utilisateur INT UNIQUE REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE,
    numero_secu VARCHAR(20) UNIQUE
);

-- Création de la table des rendez-vous
CREATE TABLE rendez_vous (
    id_rdv SERIAL PRIMARY KEY,
    id_patient INT REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE,
    id_medecin INT REFERENCES infos_medecins(id_medecin) ON DELETE CASCADE,
    date_rdv TIMESTAMP NOT NULL,
    statut VARCHAR(50) DEFAULT 'En attente', -- Statuts possibles : "Confirmé", "Annulé", "En attente"
    commentaire TEXT
);

-- Insertion des données initiales
-- Ajout des rôles
INSERT INTO roles (role_name) VALUES 
('Médecin'),
('Patient');

-- Ajout des utilisateurs
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role) VALUES
('Dupont', 'Nadia', 'nadia.dupont@example.com', 'hashed_password', 3), -- Patient
('Martin', 'Julie', 'julie.martin@example.com', 'hashed_password', 2); -- Médecin

-- Ajout des informations des médecins
INSERT INTO infos_medecins (id_utilisateur, specialite, adresse_cabinet, numero_telephone, disponibilite) 
VALUES 
(2, 'Cardiologue', '123 Rue de la Santé, Paris', '0102030405', '{"lundi": ["09:00", "17:00"], "mardi": ["10:00", "14:00"]}');

-- Ajout d'un rendez-vous
INSERT INTO rendez_vous (id_patient, id_medecin, date_rdv, statut, commentaire) 
VALUES 
(1, 1, '2025-02-15 14:00:00', 'En attente', 'Première consultation');
