-- Création de la base de données
-- CREATE DATABASE cabinet_medical;

-- Table des utilisateurs
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    role VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT check_role CHECK (role IN ('medecin', 'patient'))
);

-- Table des médecins
CREATE TABLE medecins (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    specialite VARCHAR(100) NOT NULL,
    CONSTRAINT check_medecin_role CHECK (
        user_id IN (SELECT id FROM users WHERE role = 'medecin')
    )
);

-- Table des patients
CREATE TABLE patients (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    numero_securite_sociale VARCHAR(20),
    date_naissance DATE,
    CONSTRAINT check_patient_role CHECK (
        user_id IN (SELECT id FROM users WHERE role = 'patient')
    )
);

-- Table des rendez-vous
CREATE TABLE rendez_vous (
    id SERIAL PRIMARY KEY,
    patient_id INTEGER REFERENCES patients(id),
    medecin_id INTEGER REFERENCES medecins(id),
    date_rdv DATE NOT NULL,
    heure_rdv TIME NOT NULL,
    motif TEXT,
    statut VARCHAR(20) DEFAULT 'En attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT check_rdv_statut CHECK (statut IN ('En attente', 'Confirmé', 'Annulé')),
    CONSTRAINT unique_rdv UNIQUE(medecin_id, date_rdv, heure_rdv)
);

-- Données de test
INSERT INTO users (nom, prenom, email, password, telephone, role) VALUES
('Dupont', 'Jean', 'medecin@cabinet.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0123456789', 'medecin'),
('Martin', 'Pierre', 'patient@cabinet.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0987654321', 'patient');

-- Insertion médecin
INSERT INTO medecins (user_id, specialite) VALUES
((SELECT id FROM users WHERE email = 'medecin@cabinet.com'), 'Généraliste');

-- Insertion patient
INSERT INTO patients (user_id, numero_securite_sociale, date_naissance) VALUES
((SELECT id FROM users WHERE email = 'patient@cabinet.com'), '1800175123456', '1990-01-01');