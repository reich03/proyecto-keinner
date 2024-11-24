<?php

class Database
{

    private $host;
    private $db;
    private $user;
    private $password;

    public function __construct()
    {
        $this->host = constant('HOST');
        $this->db = constant('DB');
        $this->user = constant('USER');
        $this->password = constant('PASSWORD');
    }

    function connect()
    {
        try {
            $connection = "pgsql:host=" . $this->host . ";dbname=" . $this->db;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new PDO($connection, $this->user, $this->password, $options);
            error_log('Conexión a BD exitosa');
            return $pdo;
        } catch (PDOException $e) {
            error_log('Error connection: ' . $e->getMessage());
        }
    }
}
