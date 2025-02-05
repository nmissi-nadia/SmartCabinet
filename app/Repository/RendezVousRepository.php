<?php

namespace App\Repository;

use PDO;

class RendezVousRepository implements RepositoryInterface
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("
            SELECT rv.*, 
                   p.nom as patient_nom, p.prenom as patient_prenom,
                   m.nom as medecin_nom, m.prenom as medecin_prenom
            FROM rendez_vous rv
            JOIN patients p ON rv.patient_id = p.id
            JOIN medecins m ON rv.medecin_id = m.id
            ORDER BY rv.date, rv.heure
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("
            SELECT rv.*, 
                   p.nom as patient_nom, p.prenom as patient_prenom,
                   m.nom as medecin_nom, m.prenom as medecin_prenom
            FROM rendez_vous rv
            JOIN patients p ON rv.patient_id = p.id
            JOIN medecins m ON rv.medecin_id = m.id
            WHERE rv.id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO rendez_vous (patient_id, medecin_id, date, heure, motif, statut)
            VALUES (:patient_id, :medecin_id, :date, :heure, :motif, :statut)
        ");
        
        return $stmt->execute([
            ':patient_id' => $data['patient_id'],
            ':medecin_id' => $data['medecin_id'],
            ':date' => $data['date'],
            ':heure' => $data['heure'],
            ':motif' => $data['motif'],
            ':statut' => $data['statut'] ?? 'En attente'
        ]);
    }

    public function update($id, array $data)
    {
        $stmt = $this->db->prepare("
            UPDATE rendez_vous 
            SET patient_id = :patient_id,
                medecin_id = :medecin_id,
                date = :date,
                heure = :heure,
                motif = :motif,
                statut = :statut
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':patient_id' => $data['patient_id'],
            ':medecin_id' => $data['medecin_id'],
            ':date' => $data['date'],
            ':heure' => $data['heure'],
            ':motif' => $data['motif'],
            ':statut' => $data['statut']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM rendez_vous WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function findByMedecin($medecinId)
    {
        $stmt = $this->db->prepare("
            SELECT rv.*, 
                   p.nom as patient_nom, p.prenom as patient_prenom
            FROM rendez_vous rv
            JOIN patients p ON rv.patient_id = p.id
            WHERE rv.medecin_id = :medecin_id
            ORDER BY rv.date, rv.heure
        ");
        $stmt->bindParam(':medecin_id', $medecinId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByPatient($patientId)
    {
        $stmt = $this->db->prepare("
            SELECT rv.*, 
                   m.nom as medecin_nom, m.prenom as medecin_prenom
            FROM rendez_vous rv
            JOIN medecins m ON rv.medecin_id = m.id
            WHERE rv.patient_id = :patient_id
            ORDER BY rv.date, rv.heure
        ");
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE rendez_vous 
            SET statut = :statut
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':statut' => $status
        ]);
    }
}
