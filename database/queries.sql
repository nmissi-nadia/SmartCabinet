-- Requêtes pour les utilisateurs
-- Connexion utilisateur
SELECT * FROM users WHERE email = :email;

-- Liste des médecins
SELECT u.*, m.specialite
FROM users u
JOIN medecins m ON u.id = m.user_id
WHERE u.role = 'medecin';

-- Liste des patients
SELECT u.*, p.numero_securite_sociale, p.date_naissance
FROM users u
JOIN patients p ON u.id = p.user_id
WHERE u.role = 'patient';

-- Rendez-vous d'un patient
SELECT rv.*,
       u.nom as medecin_nom,
       u.prenom as medecin_prenom,
       m.specialite
FROM rendez_vous rv
JOIN medecins m ON rv.medecin_id = m.id
JOIN users u ON m.user_id = u.id
WHERE rv.patient_id = :patient_id
ORDER BY rv.date_rdv, rv.heure_rdv;

-- Rendez-vous d'un médecin
SELECT rv.*,
       u.nom as patient_nom,
       u.prenom as patient_prenom
FROM rendez_vous rv
JOIN patients p ON rv.patient_id = p.id
JOIN users u ON p.user_id = u.id
WHERE rv.medecin_id = :medecin_id
AND rv.date_rdv = CURRENT_DATE
ORDER BY rv.heure_rdv;

-- Statistiques simples
-- Nombre de rendez-vous par médecin
SELECT 
    u.nom,
    u.prenom,
    m.specialite,
    COUNT(rv.id) as nombre_rdv
FROM users u
JOIN medecins m ON u.id = m.user_id
LEFT JOIN rendez_vous rv ON m.id = rv.medecin_id
GROUP BY u.id, u.nom, u.prenom, m.specialite;
