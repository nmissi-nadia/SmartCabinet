<?php
namespace App\Models;

use App\Core\Application;

class Patient extends Model {
    public static string $table = 'infos_patients';
    
    public int $id_patient;
    public int $id_utilisateur;
    public string $numero_secu;
    
    public function rules(): array {
        return [
            'numero_secu' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 13], [self::RULE_MAX, 'max' => 20]],
        ];
    }
    
    public function getUser() {
        return User::findOne(['id_utilisateur' => $this->id_utilisateur]);
    }
    
    public function getRendezVous() {
        return RendezVous::findAll(['id_patient' => $this->id_utilisateur]);
    }
    
    public static function create(array $data): bool {
        $db = Application::$app->getDatabase();
        $stmt = $db->prepare("
            INSERT INTO infos_patients (id_utilisateur, numero_secu)
            VALUES (:id_utilisateur, :numero_secu)
        ");
        
        return $stmt->execute([
            'id_utilisateur' => $data['id_utilisateur'],
            'numero_secu' => $data['numero_secu']
        ]);
    }
    
    public function update(): bool {
        $db = Application::$app->getDatabase();
        $stmt = $db->prepare("
            UPDATE infos_patients 
            SET numero_secu = :numero_secu
            WHERE id_utilisateur = :id_utilisateur
        ");
        
        return $stmt->execute([
            'numero_secu' => $this->numero_secu,
            'id_utilisateur' => $this->id_utilisateur
        ]);
    }
    
    public static function findByUserId(int $id_utilisateur) {
        return self::findOne(['id_utilisateur' => $id_utilisateur]);
    }
}
