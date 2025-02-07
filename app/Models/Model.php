<?php
namespace App\Models;

use App\Core\Application;

abstract class Model {
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_IN = 'in';

    protected array $errors = [];
    protected static string $table = '';

    abstract public function rules(): array;

    public function validate(): bool {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute} ?? '';
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (is_array($rule)) {
                    $ruleName = $rule[0];
                }

                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, 'Ce champ est requis');
                }

                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, 'Cet email n\'est pas valide');
                }

                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, "Ce champ doit contenir au moins {$rule['min']} caractères");
                }

                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, "Ce champ ne peut pas dépasser {$rule['max']} caractères");
                }

                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addError($attribute, 'Les champs ne correspondent pas');
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $attribute;
                    $tableName = $className::$table;
                    
                    $db = Application::$app->getDatabase();
                    $statement = $db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetch();
                    if ($record) {
                        $this->addError($attribute, 'Cette valeur existe déjà');
                    }
                }

                if ($ruleName === self::RULE_IN && !in_array($value, $rule['options'])) {
                    $this->addError($attribute, 'Valeur non valide');
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $message): void {
        $this->errors[$attribute][] = $message;
    }

    public function hasError(string $attribute): bool {
        return !empty($this->errors[$attribute]);
    }

    public function getFirstError(string $attribute): string {
        return $this->errors[$attribute][0] ?? '';
    }

    protected static function prepare(string $sql): \PDOStatement {
        return Application::$app->getDatabase()->prepare($sql);
    }

    public static function findAll(array $where = []): array {
        $tableName = static::$table;
        $attributes = array_keys($where);
        $sql = "SELECT * FROM $tableName";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        }
        
        $stmt = self::prepare($sql);
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public static function findOne(array $where): ?static {
        $tableName = static::$table;
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }
}
