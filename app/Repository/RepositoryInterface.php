<?php

namespace App\Repository;

interface RepositoryInterface
{
    public function findAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
