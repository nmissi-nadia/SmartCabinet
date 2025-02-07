<?php
namespace App\Models;

use App\Core\Application;

class User extends Model {
    protected static string $table = 'utilisateurs';
    
    public int $id_utilisateur;
    public string $nom;
    public string $prenom;
    public string $email;
    public string $password;
    public string $role;
    
    public function rules(): array {
        return [
            'nom' => [self::RULE_REQUIRED],
            'prenom' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'role' => [self::RULE_REQUIRED, [self::RULE_IN, 'options' => ['patient', 'medecin', 'admin']]],
        ];
    }
    
    public function getFullName(): string {
        return $this->nom . ' ' . $this->prenom;
    }
    
    public function save(): bool {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }
    
    public function update(): bool {
        $db = Application::$app->getDatabase();
        $stmt = $db->prepare("
            UPDATE utilisateurs 
            SET nom = :nom, 
                prenom = :prenom, 
                email = :email
            WHERE id_utilisateur = :id_utilisateur
        ");
        
        return $stmt->execute([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'id_utilisateur' => $this->id_utilisateur
        ]);
    }
    
    public static function findOne($where): ?static {
        $tableName = static::$table;
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }
    
    public static function findByEmail(string $email): ?static {
        return static::findOne(['email' => $email]);
    }
}
