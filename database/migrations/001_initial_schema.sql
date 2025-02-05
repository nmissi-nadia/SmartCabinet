-- Migration: 001_initial_schema
-- Description: Création du schéma initial de la base de données
-- Date: 2025-02-04

BEGIN;

-- Table de suivi des migrations
CREATE TABLE IF NOT EXISTS migrations (
    id SERIAL PRIMARY KEY,
    version VARCHAR(50) NOT NULL,
    description TEXT,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table utilisateur générale
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    date_naissance DATE,
    adresse TEXT,
    role VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pour les informations spécifiques aux patients
CREATE TABLE IF NOT EXISTS patient_details (
    user_id INTEGER PRIMARY KEY REFERENCES users(id),
    numero_securite_sociale VARCHAR(20),
    groupe_sanguin VARCHAR(3),
    antecedents_medicaux TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pour les informations spécifiques aux médecins
CREATE TABLE IF NOT EXISTS medecin_details (
    user_id INTEGER PRIMARY KEY REFERENCES users(id),
    specialite VARCHAR(100) NOT NULL,
    numero_rpps VARCHAR(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des disponibilités des médecins
CREATE TABLE IF NOT EXISTS disponibilites (
    id SERIAL PRIMARY KEY,
    medecin_id INTEGER REFERENCES users(id),
    jour_semaine INTEGER,
    date_specifique DATE,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    pause_debut TIME,
    pause_fin TIME,
    est_disponible BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_medecin_role CHECK (
        medecin_id IN (SELECT id FROM users WHERE role = 'medecin')
    ),
    CONSTRAINT chk_jour_or_date CHECK (
        (jour_semaine IS NOT NULL AND date_specifique IS NULL) OR
        (jour_semaine IS NULL AND date_specifique IS NOT NULL)
    )
);

-- Table des rendez-vous
CREATE TABLE IF NOT EXISTS rendez_vous (
    id SERIAL PRIMARY KEY,
    patient_id INTEGER REFERENCES users(id),
    medecin_id INTEGER REFERENCES users(id),
    date DATE NOT NULL,
    heure TIME NOT NULL,
    duree INTEGER DEFAULT 30,
    motif TEXT,
    statut VARCHAR(20) DEFAULT 'En attente',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_patient_role CHECK (
        patient_id IN (SELECT id FROM users WHERE role = 'patient')
    ),
    CONSTRAINT chk_medecin_role_rdv CHECK (
        medecin_id IN (SELECT id FROM users WHERE role = 'medecin')
    ),
    UNIQUE(medecin_id, date, heure)
);

-- Création des index
CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);
CREATE INDEX IF NOT EXISTS idx_disponibilites_medecin ON disponibilites(medecin_id);
CREATE INDEX IF NOT EXISTS idx_rdv_date ON rendez_vous(date);
CREATE INDEX IF NOT EXISTS idx_rdv_medecin_date ON rendez_vous(medecin_id, date);
CREATE INDEX IF NOT EXISTS idx_rdv_patient ON rendez_vous(patient_id);

-- Enregistrement de la migration
INSERT INTO migrations (version, description) 
VALUES ('001', 'Initial schema creation');

COMMIT;
