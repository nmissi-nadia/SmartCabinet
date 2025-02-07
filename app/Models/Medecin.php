<?php
namespace App\Models;

class Medecin extends Model {
    protected static string $table = 'infos_medecins';
    
    public function validate(): bool {
        $this->errors = [];
        
        if (empty($this->attributes['specialite'])) {
            $this->errors['specialite'] = "La spécialité est requise";
        }
        
        if (empty($this->attributes['numero_telephone'])) {
            $this->errors['numero_telephone'] = "Le numéro de téléphone est requis";
        } elseif (!preg_match('/^[0-9+\-\s()]{10,20}$/', $this->attributes['numero_telephone'])) {
            $this->errors['numero_telephone'] = "Le numéro de téléphone n'est pas valide";
        }
        
        if (!empty($this->attributes['disponibilite'])) {
            if (!is_array($this->attributes['disponibilite'])) {
                $this->errors['disponibilite'] = "Le format des disponibilités n'est pas valide";
            }
        }
        
        return empty($this->errors);
    }
    
    public function getUser(): ?User {
        return User::findOne(['id_utilisateur' => $this->id_utilisateur]);
    }
    
    public function getRendezVous($date = null): array {
        $conditions = ['id_medecin' => $this->id_medecin];
        if ($date) {
            $conditions['DATE(date_rdv)'] = $date;
        }
        return RendezVous::findAll($conditions);
    }
    
    public function setDisponibilite(array $disponibilite): void {
        $this->attributes['disponibilite'] = json_encode($disponibilite);
    }
    
    public function getDisponibilite(): array {
        return json_decode($this->attributes['disponibilite'], true) ?? [];
    }
    
    public function isDisponible(string $jour, string $heure): bool {
        $disponibilites = $this->getDisponibilite();
        if (!isset($disponibilites[$jour])) {
            return false;
        }
        
        $plageHoraire = $disponibilites[$jour];
        if (count($plageHoraire) !== 2) {
            return false;
        }
        
        $debut = strtotime($plageHoraire[0]);
        $fin = strtotime($plageHoraire[1]);
        $heureTest = strtotime($heure);
        
        return $heureTest >= $debut && $heureTest <= $fin;
    }
    
    public static function findByUserId(int $userId): ?static {
        return static::findOne(['id_utilisateur' => $userId]);
    }
}
