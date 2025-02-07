<?php
namespace App\Models;

class User extends Model {
    protected static string $table = 'utilisateurs';
    
    public function validate(): bool {
        $this->errors = [];
        
        if (empty($this->attributes['email'])) {
            $this->errors['email'] = "L'email est requis";
        } elseif (!filter_var($this->attributes['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "L'email n'est pas valide";
        }
        
        if (empty($this->attributes['mot_de_passe'])) {
            $this->errors['mot_de_passe'] = "Le mot de passe est requis";
        }
        
        if (empty($this->attributes['nom'])) {
            $this->errors['nom'] = "Le nom est requis";
        }
        
        if (empty($this->attributes['prenom'])) {
            $this->errors['prenom'] = "Le prénom est requis";
        }
        
        if (empty($this->attributes['id_role'])) {
            $this->errors['id_role'] = "Le rôle est requis";
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
        if ($this->id_role !== 2) { // 2 = Médecin
            return null;
        }
        return Medecin::findOne(['id_utilisateur' => $this->id_utilisateur]);
    }
    
    public function getRendezVous(): array {
        if ($this->id_role === 3) { // 3 = Patient
            return RendezVous::findAll(['id_patient' => $this->id_utilisateur]);
        }
        return [];
    }
}
