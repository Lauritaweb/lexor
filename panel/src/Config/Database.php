<?php

namespace App\Config;

use mysqli;
use Dotenv\Dotenv;

class Database
{
    private $connection;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->connection = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_NAME'],
            $_ENV['DB_PORT']
        );

        if ($this->connection->connect_error) 
            die("Connection failed: " . $this->connection->connect_error);
        
        if (!$this->connection->set_charset("utf8mb4")) 
            die("Error loading character set utf8mb4: " . $this->connection->error);
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
