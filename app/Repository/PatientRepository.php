<?php

namespace App\Repository;

use PDO;

class PatientRepository implements RepositoryInterface
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM patients");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO patients (nom, prenom, email, telephone, date_naissance)
            VALUES (:nom, :prenom, :email, :telephone, :date_naissance)
        ");
        
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':date_naissance' => $data['date_naissance']
        ]);
    }

    public function update($id, array $data)
    {
        $stmt = $this->db->prepare("
            UPDATE patients 
            SET nom = :nom,
                prenom = :prenom,
                email = :email,
                telephone = :telephone,
                date_naissance = :date_naissance
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':date_naissance' => $data['date_naissance']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM patients WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
