<?php

namespace App\Repository;

use PDO;

class MedecinRepository implements RepositoryInterface
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM medecins");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM medecins WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO medecins (nom, prenom, email, telephone, specialite)
            VALUES (:nom, :prenom, :email, :telephone, :specialite)
        ");
        
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':specialite' => $data['specialite']
        ]);
    }

    public function update($id, array $data)
    {
        $stmt = $this->db->prepare("
            UPDATE medecins 
            SET nom = :nom,
                prenom = :prenom,
                email = :email,
                telephone = :telephone,
                specialite = :specialite
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':specialite' => $data['specialite']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM medecins WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function findAvailableSlots($medecinId, $date)
    {
        $stmt = $this->db->prepare("
            SELECT h.heure
            FROM horaires h
            LEFT JOIN rendez_vous rv ON rv.medecin_id = :medecin_id 
                AND rv.date = :date 
                AND rv.heure = h.heure
            WHERE rv.id IS NULL
        ");
        
        $stmt->execute([
            ':medecin_id' => $medecinId,
            ':date' => $date
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
