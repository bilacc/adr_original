<?php
class Database
{
    /** @var PDO */
    public $pdo;
    public function __construct()
    {
        if (!get_conf('use_database')) {
            return;
        }
        $host = get_conf('database_host');
        $db   = get_conf('database_name');
        $user = get_conf('database_username');
        $pass = get_conf('database_password');
        $charset = 'utf8mb4';
        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // In production you might log and show a generic message
            die("Database connection failed: " . $e->getMessage());
        }
        // expose to global scope as older code expects $db var
        $GLOBALS['db_instance'] = $this;
    }

    public function queryAll($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function queryRow($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function queryOne($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        if ($row) {
            return array_values($row)[0];
        }
        return null;
    }

    public function exec($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}