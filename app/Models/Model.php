<?php
namespace App\Models;

use PDO;

abstract class Model
{
    protected static $db;

    public static function getDB()
    {
        if (!isset(self::$db)) {
            $dsn = "pgsql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'];
            self::$db = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$db;
    }

    abstract public static function findAll();
    abstract public static function findById($id);
    abstract public function save();
    abstract public function delete();
}
