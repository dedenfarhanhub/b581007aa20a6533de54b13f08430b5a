<?php

namespace App;

class DB
{
    private static ?DB $instance = null;
    private static string $host = DB_HOST;
    private static string $port = DB_PORT;
    private static string $dbname = DB_NAME;
    private static string $user = DB_USER;
    private static string $pass = DB_PASS;
    private \PDO $pdo;

    public function __construct()
    {
        $dsn = 'pgsql:host=' . self::$host . ';port=' . self::$port . ';dbname=' . self::$dbname . ';';
        $this->pdo = new \PDO($dsn, self::$user, self::$pass);
    }

    public static function getInstance(): DB
    {
        if (!self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }
}
