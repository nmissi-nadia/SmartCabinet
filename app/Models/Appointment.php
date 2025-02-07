<?php
namespace App\Models;

use App\Core\Application;

class Appointment extends Model {
    public static string $table = 'rendez_vous';
    
    public int $id_rendez_vous;
    public int $id_patient;
    public int $id_medecin;
    public string $date_heure;
    public string $motif;
    public string $status = 'en_attente';
    public ?string $notes = null;
    
    public function rules(): array {
        return [
            'id_patient' => [self::RULE_REQUIRED],
            'id_medecin' => [self::RULE_REQUIRED],
            'date_heure' => [self::RULE_REQUIRED],
            'motif' => [self::RULE_REQUIRED],
            'status' => [[self::RULE_IN, 'values' => ['en_attente', 'confirme', 'annule', 'termine']]],
            'notes' => []
        ];
    }
    
    public function getMedecin() {
        return Medecin::findOne(['id_medecin' => $this->id_medecin]);
    }
    
    public function getPatient() {
        return Patient::findOne(['id_patient' => $this->id_patient]);
    }
    
    public static function findAllByPatient(int $userId): array {
        $patient = Patient::findOne(['id_utilisateur' => $userId]);
        if (!$patient) {
            return [];
        }
        
        return self::findAll(['id_patient' => $patient->id_patient]);
    }
    
    public function save(): bool {
        $db = Application::$app->getDatabase();
        
        if ($this->id_rendez_vous) {
            // Update
            $stmt = $db->prepare("
                UPDATE rendez_vous 
                SET date_heure = :date_heure,
                    motif = :motif,
                    status = :status,
                    notes = :notes
                WHERE id_rendez_vous = :id
            ");
            
            return $stmt->execute([
                'date_heure' => $this->date_heure,
                'motif' => $this->motif,
                'status' => $this->status,
                'notes' => $this->notes,
                'id' => $this->id_rendez_vous
            ]);
        } else {
            // Insert
            $stmt = $db->prepare("
                INSERT INTO rendez_vous (id_patient, id_medecin, date_heure, motif, status, notes)
                VALUES (:id_patient, :id_medecin, :date_heure, :motif, :status, :notes)
            ");
            
            return $stmt->execute([
                'id_patient' => $this->id_patient,
                'id_medecin' => $this->id_medecin,
                'date_heure' => $this->date_heure,
                'motif' => $this->motif,
                'status' => $this->status,
                'notes' => $this->notes
            ]);
        }
    }
    
    public function cancel(): bool {
        $this->status = 'annule';
        return $this->save();
    }
    
    public function confirm(): bool {
        $this->status = 'confirme';
        return $this->save();
    }
    
    public function complete(): bool {
        $this->status = 'termine';
        return $this->save();
    }
}
