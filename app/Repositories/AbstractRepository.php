<?php

namespace App\Repositories;

use PDO;
use Exception;

abstract class AbstractRepository implements RepositoryInterface
{
    protected PDO $db;
    protected string $table;
    protected string $model;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->model);
    }

    public function getById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject($this->model);
    }

    public function create(array $data)
    {
        $fields = array_keys($data);
        $values = array_map(fn($field) => ":$field", $fields);
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $fields),
            implode(', ', $values)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data)
    {
        $fields = array_map(fn($field) => "$field = :$field", array_keys($data));
        
        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $this->table,
            implode(', ', $fields)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge($data, ['id' => $id]));
        
        return $stmt->rowCount();
    }

    public function delete(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    protected function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    protected function commit()
    {
        $this->db->commit();
    }

    protected function rollback()
    {
        $this->db->rollBack();
    }
}
