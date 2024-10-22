<?php

class Db
{
    private $connection;
    private static $db;

    public static function getInstance()
    {
        if (self::$db == null) {
            self::$db = new Db();
        }

        return self::$db;
    }

    private function __construct()
{
    try {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $name = 'users';

        $this->connection = new mysqli($host, $user, $pass, $name);
        if ($this->connection->connect_error) {
            throw new Exception("Connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8");
    } catch (Exception $e) {
        $this->logError("Database Connection Error: " . $e->getMessage());
        die("Database connection error. Please try again later.");
    }
}


    private function executeQuery($sql, $types = "", $params = [])
{
    try {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        return $stmt;
    } catch (Exception $e) {
        $this->logError("Database Error: " . $e->getMessage());
        return false;
    }
}


    public function query($sql, $types = "", $params = [])
    {
        $stmt = $this->executeQuery($sql, $types, $params);
        if (!$stmt) {
            $this->logError("Query failed: " . $sql);
            return null;
        }

        $result = $stmt->get_result();
        $records = $result ? $result->fetch_all(MYSQLI_ASSOC) : null;
        $stmt->close();

        return $records;
    }

    public function insert($sql, $types = "", $params = [])
    {
        $stmt = $this->executeQuery($sql, $types, $params);
        if (!$stmt) {
            $this->logError("Insert failed: " . $sql);
            return false;
        }

        $insertId = $stmt->insert_id;
        $stmt->close();

        return $insertId;
    }

    public function modify($sql, $types = "", $params = [])
    {
        $stmt = $this->executeQuery($sql, $types, $params);
        if (!$stmt) {
            $this->logError("Modify failed: " . $sql);
            return false;
        }

        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows;
    }

    public function logError($message)
    {
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ": " . $message . PHP_EOL, FILE_APPEND);
    }

    public function close()
    {
        $this->connection->close();
    }
}
