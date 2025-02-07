<?php
namespace App\Models;

use App\Core\Application;

abstract class Model {
    protected static string $table;
    protected array $attributes = [];
    protected array $errors = [];
    
    public function __construct(array $attributes = []) {
        $this->attributes = $attributes;
    }
    
    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }
    
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function validate(): bool {
        return true;
    }
    
    public function save(): bool {
        if (!$this->validate()) {
            return false;
        }
        
        $db = Application::$app->getDatabase();
        $table = static::$table;
        $attributes = $this->attributes;
        
        if (!isset($attributes['id'])) {
            // Insert
            $columns = implode(',', array_keys($attributes));
            $values = implode(',', array_fill(0, count($attributes), '?'));
            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute(array_values($attributes));
        } else {
            // Update
            $id = $attributes['id'];
            unset($attributes['id']);
            
            $set = implode('=?,', array_keys($attributes)) . '=?';
            $sql = "UPDATE $table SET $set WHERE id=?";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute([...array_values($attributes), $id]);
        }
    }
    
    public static function findOne($conditions): ?static {
        $db = Application::$app->getDatabase();
        $table = static::$table;
        
        $where = [];
        $params = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = ?";
            $params[] = $value;
        }
        
        $whereStr = implode(' AND ', $where);
        $sql = "SELECT * FROM $table WHERE $whereStr LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }
        
        return new static($result);
    }
    
    public static function findAll($conditions = []): array {
        $db = Application::$app->getDatabase();
        $table = static::$table;
        
        $sql = "SELECT * FROM $table";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $whereStr = implode(' AND ', $where);
            $sql .= " WHERE $whereStr";
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($result) => new static($result), $results);
    }
}
