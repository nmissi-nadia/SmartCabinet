<?php
namespace App\Models;

use App\Core\Application;

class Patient extends Model {
    public static string $table = 'patients';
    
    public int $id_patient;
    public int $id_utilisateur;
    public string $numero_securite_sociale;
    public ?string $antecedents_medicaux = null;
    public ?string $allergies = null;
    public ?string $groupe_sanguin = null;
    
    public function rules(): array {
        return [
            'numero_securite_sociale' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 13], [self::RULE_MAX, 'max' => 15]],
            'antecedents_medicaux' => [],
            'allergies' => [],
            'groupe_sanguin' => [[self::RULE_IN, 'values' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']]]
        ];
    }
    
    public function getUser() {
        return User::findOne(['id_utilisateur' => $this->id_utilisateur]);
    }
    
    public function getAppointments() {
        return Appointment::findAll(['id_patient' => $this->id_patient]);
    }
    
    public function update(): bool {
        $db = Application::$app->getDatabase();
        $stmt = $db->prepare("
            UPDATE patients 
            SET antecedents_medicaux = :antecedents, 
                allergies = :allergies, 
                groupe_sanguin = :groupe
            WHERE id_patient = :id
        ");
        
        return $stmt->execute([
            'antecedents' => $this->antecedents_medicaux,
            'allergies' => $this->allergies,
            'groupe_sanguin' => $this->groupe_sanguin,
            'id' => $this->id_patient
        ]);
    }
}
