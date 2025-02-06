<?php
namespace App\Models;

class RendezVous extends Model
{
    public $id;
    public $date;
    public $heure;
    public $patient_id;
    public $medecin_id;
    public $motif;
    public $statut;

    public static function findAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM rendez_vous');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM rendez_vous WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save()
    {
        $db = static::getDB();
        if ($this->id) {
            $stmt = $db->prepare('UPDATE rendez_vous SET date = :date, heure = :heure, patient_id = :patient_id, medecin_id = :medecin_id, motif = :motif, statut = :statut WHERE id = :id');
        } else {
            $stmt = $db->prepare('INSERT INTO rendez_vous (date, heure, patient_id, medecin_id, motif, statut) VALUES (:date, :heure, :patient_id, :medecin_id, :motif, :statut)');
        }
        $stmt->execute([
            'date' => $this->date,
            'heure' => $this->heure,
            'patient_id' => $this->patient_id,
            'medecin_id' => $this->medecin_id,
            'motif' => $this->motif,
            'statut' => $this->statut,
            'id' => $this->id
        ]);
    }

    public function delete()
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM rendez_vous WHERE id = :id');
        $stmt->execute(['id' => $this->id]);
    }
}