<?php

class DatabaseConnection {

    private $connection;

    public function __construct($config) {
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? '3306';
        $dbname = $config['dbname'];
        $charset = $config['charset'] ?? 'utf8mb4';
        $username = $config['username'];
        $password = $config['password'];

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage()); // Log error details
            die("Database connection error: " . $e->getMessage()); // Show detailed error for debugging
        }
    }

    public function run($sql, $params = []) {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            error_log("SQL error: " . $e->getMessage());
            die("Database query error: " . $e->getMessage()); // Show detailed error for debugging
        }
    }

    public function close_connection() {
        $this->connection = null;
    }
}
