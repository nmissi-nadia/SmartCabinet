<?php
namespace App\Models;

class User extends Model {
    protected static string $table = 'utilisateurs';
    
    public function validate(): bool {
        // Validation des champs requis
        $this->validateRequired('email', 'Email');
        $this->validateRequired('mot_de_passe', 'Mot de passe');
        $this->validateRequired('nom', 'Nom');
        $this->validateRequired('prenom', 'Prénom');
        $this->validateRequired('role', 'Rôle');

        // Validation du format email
        $this->validateEmail('email');

        // Validation de la longueur du mot de passe
        $this->validateMinLength('mot_de_passe', 6, 'Mot de passe');
        $this->validateMaxLength('mot_de_passe', 100, 'Mot de passe');

        // Validation des longueurs pour nom et prénom
        $this->validateMaxLength('nom', 50, 'Nom');
        $this->validateMaxLength('prenom', 50, 'Prénom');

        // Vérification que l'email est unique
        $this->validateUnique('email');

        // Validation du rôle
        $value = $this->attributes['role'] ?? '';
        if (!in_array($value, ['patient', 'medecin'])) {
            $this->addError('role', 'Le rôle doit être soit patient soit médecin');
        }

        return empty($this->errors);
    }
    
    public function setPassword(string $password): void {
        $this->attributes['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->attributes['mot_de_passe']);
    }
    
    public static function findByEmail(string $email): ?static {
        return static::findOne(['email' => $email]);
    }
    
    public function getMedecin(): ?Medecin {
        if ($this->id_role !== 1) { // 2 = Médecin
            return null;
        }
        return Medecin::findOne(['id_utilisateur' => $this->id_utilisateur]);
    }
    
    public function getRendezVous(): array {
        if ($this->id_role === 2) { // 3 = Patient
            return RendezVous::findAll(['id_patient' => $this->id_utilisateur]);
        }
        return [];
    }
}
