<?php
namespace App\Models;

class Role extends Model {
    protected static string $table = 'roles';
    
    public const ADMIN = 1;
    public const MEDECIN = 2;
    public const PATIENT = 3;
    
    public function validate(): bool {
        $this->errors = [];
        
        if (empty($this->attributes['role_name'])) {
            $this->errors['role_name'] = "Le nom du rôle est requis";
        } elseif (!in_array($this->attributes['role_name'], ['Admin', 'Médecin', 'Patient'])) {
            $this->errors['role_name'] = "Le nom du rôle n'est pas valide";
        }
        
        return empty($this->errors);
    }
    
    public static function getRoleName(int $roleId): string {
        $role = static::findOne(['id_role' => $roleId]);
        return $role ? $role->role_name : '';
    }
    
    public static function getUsers(int $roleId): array {
        return User::findAll(['id_role' => $roleId]);
    }
}
