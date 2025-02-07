<?php
namespace App\Models;

class RendezVous extends Model {
    protected static string $table = 'rendez_vous';
    
    public function validate(): bool {
        $this->errors = [];
        
        if (empty($this->attributes['id_patient'])) {
            $this->errors['id_patient'] = "L'ID du patient est requis";
        }
        
        if (empty($this->attributes['id_medecin'])) {
            $this->errors['id_medecin'] = "L'ID du médecin est requis";
        }
        
        if (empty($this->attributes['date_rdv'])) {
            $this->errors['date_rdv'] = "La date du rendez-vous est requise";
        } elseif (strtotime($this->attributes['date_rdv']) < strtotime('today')) {
            $this->errors['date_rdv'] = "La date du rendez-vous ne peut pas être dans le passé";
        }
        
        if (!empty($this->attributes['statut']) && 
            !in_array($this->attributes['statut'], ['En attente', 'Confirmé', 'Annulé'])) {
            $this->errors['statut'] = "Le statut n'est pas valide";
        }
        
        // Vérifier la disponibilité du médecin
        if (empty($this->errors)) {
            $medecin = Medecin::findOne(['id_medecin' => $this->attributes['id_medecin']]);
            if ($medecin) {
                $jour = strtolower(strftime('%A', strtotime($this->attributes['date_rdv'])));
                $heure = date('H:i', strtotime($this->attributes['date_rdv']));
                
                if (!$medecin->isDisponible($jour, $heure)) {
                    $this->errors['date_rdv'] = "Le médecin n'est pas disponible à cette date et heure";
                }
            }
        }
        
        return empty($this->errors);
    }
    
    public function getPatient(): ?User {
        return User::findOne(['id_utilisateur' => $this->id_patient]);
    }
    
    public function getMedecin(): ?Medecin {
        return Medecin::findOne(['id_medecin' => $this->id_medecin]);
    }
    
    public static function findUpcoming(int $patientId): array {
        $db = Application::$app->getDatabase();
        $sql = "SELECT * FROM rendez_vous 
                WHERE id_patient = ? 
                AND date_rdv >= CURRENT_TIMESTAMP 
                AND statut != 'Annulé'
                ORDER BY date_rdv";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$patientId]);
        
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($result) => new static($result), $results);
    }
    
    public static function findByMedecinAndDate(int $medecinId, string $date): array {
        return static::findAll([
            'id_medecin' => $medecinId,
            'DATE(date_rdv)' => $date
        ]);
    }
}
