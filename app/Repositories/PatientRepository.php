<?php

namespace App\Repositories;

use App\Models\Patient;
use PDO;

class PatientRepository extends AbstractRepository
{
    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->table = 'patients';
        $this->model = Patient::class;
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, u.* 
            FROM patients p
            JOIN users u ON p.user_id = u.id
            WHERE u.email = :email
        ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchObject($this->model);
    }

    public function getUpcomingAppointments(int $patientId)
    {
        $stmt = $this->db->prepare("
            SELECT rv.*, m.specialite, u.nom as medecin_nom, u.prenom as medecin_prenom
            FROM rendez_vous rv
            JOIN medecins m ON rv.medecin_id = m.id
            JOIN users u ON m.user_id = u.id
            WHERE rv.patient_id = :patient_id
            AND rv.date_rdv >= CURRENT_DATE
            ORDER BY rv.date_rdv, rv.heure_rdv
        ");
        $stmt->execute(['patient_id' => $patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createWithUser(array $userData, array $patientData)
    {
        try {
            $this->beginTransaction();

            // Créer l'utilisateur
            $stmt = $this->db->prepare("
                INSERT INTO users (nom, prenom, email, password, telephone, role)
                VALUES (:nom, :prenom, :email, :password, :telephone, 'patient')
            ");
            $stmt->execute($userData);
            $userId = $this->db->lastInsertId();

            // Créer le patient
            $patientData['user_id'] = $userId;
            $stmt = $this->db->prepare("
                INSERT INTO patients (user_id, numero_securite_sociale, date_naissance)
                VALUES (:user_id, :numero_securite_sociale, :date_naissance)
            ");
            $stmt->execute($patientData);

            $this->commit();
            return $userId;

        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}
