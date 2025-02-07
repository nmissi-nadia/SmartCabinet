<?php
namespace App\Models;

use App\Core\Application;

class RendezVous extends Model {
    protected static string $table = 'rendez_vous';
    
    public int $id_rdv;
    public int $id_patient;
    public int $id_medecin;
    public string $date_rdv;
    public string $statut = 'En attente';
    public ?string $commentaire = null;
    public ?string $medecin_nom = null;
    public ?string $prenom = null;
    public ?string $specialite = null;
    
    public function rules(): array {
        return [
            'id_patient' => [self::RULE_REQUIRED],
            'id_medecin' => [self::RULE_REQUIRED],
            'date_rdv' => [self::RULE_REQUIRED],
            'statut' => [self::RULE_REQUIRED, [self::RULE_IN, 'options' => ['En attente', 'Confirmé', 'Annulé']]],
        ];
    }
    
    public function validate(): bool {
        if (!parent::validate()) {
            return false;
        }
        
        // Vérifier si la date n'est pas dans le passé
        if (strtotime($this->date_rdv) < strtotime('today')) {
            $this->addError('date_rdv', "La date du rendez-vous ne peut pas être dans le passé");
            return false;
        }
        
        // Vérifier la disponibilité du médecin
        $medecin = $this->getMedecin();
        if ($medecin) {
            $jour = strtolower(date('l', strtotime($this->date_rdv)));
            $heure = date('H:i', strtotime($this->date_rdv));
            
            if (!$medecin->isDisponible($jour, $heure)) {
                $this->addError('date_rdv', "Le médecin n'est pas disponible à cette date et heure");
                return false;
            }
        }
        
        return true;
    }
    
    public function getPatient(): ?User {
        return User::findOne(['id_utilisateur' => $this->id_patient]);
    }
    
    public function getMedecin(): ?Medecin {
        return Medecin::findOne(['id_medecin' => $this->id_medecin]);
    }
    
    public static function findAllByPatient(int $patientId): array {
        $db = Application::$app->getDatabase();
        $sql = "
            SELECT rdv.*, 
                   m.nom as medecin_nom, m.prenom,
                   im.specialite
            FROM rendez_vous rdv
            JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
            JOIN utilisateurs m ON im.id_utilisateur = m.id_utilisateur
            WHERE rdv.id_patient = ?
            ORDER BY rdv.date_rdv DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$patientId]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        
        return $stmt->fetchAll();
    }
    
    public static function findUpcoming(int $patientId): array {
        $db = Application::$app->getDatabase();
        $sql = "
            SELECT rdv.*, 
                   m.nom as medecin_nom, m.prenom,
                   im.specialite
            FROM rendez_vous rdv
            JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
            JOIN utilisateurs m ON im.id_utilisateur = m.id_utilisateur
            WHERE rdv.id_patient = ? 
            AND rdv.date_rdv >= CURRENT_TIMESTAMP 
            AND rdv.statut != 'Annulé'
            ORDER BY rdv.date_rdv";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$patientId]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        
        return $stmt->fetchAll();
    }
    
    public static function findByMedecinAndDate(int $medecinId, string $date): array {
        $db = Application::$app->getDatabase();
        $sql = "
            SELECT rdv.*, 
                   p.nom as patient_nom, p.prenom as patient_prenom
            FROM rendez_vous rdv
            JOIN utilisateurs p ON rdv.id_patient = p.id_utilisateur
            WHERE rdv.id_medecin = ? 
            AND DATE(rdv.date_rdv) = ?
            ORDER BY rdv.date_rdv";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$medecinId, $date]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        
        return $stmt->fetchAll();
    }

    public static function confirmerRdv(int $id_rdv): bool {
        try {
            $db = Application::$app->getDatabase();
            $sql = "UPDATE rendez_vous SET statut = 'Confirmé' WHERE id_rdv = :id_rdv";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_rdv', $id_rdv, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erreur lors de la confirmation du rendez-vous: " . $e->getMessage());
            return false;
        }
    }

    public static function annulerRdv(int $id_rdv): bool {
        try {
            $db = Application::$app->getDatabase();
            $sql = "UPDATE rendez_vous SET statut = 'Annulé' WHERE id_rdv = :id_rdv";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_rdv', $id_rdv, \PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'annulation du rendez-vous: " . $e->getMessage());
            return false;
        }
    }
}
