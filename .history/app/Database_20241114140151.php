<?php
namespace app;
use PDO;

// db.php
class Database {
    private $host = _SERVER;
    private $db_name = _BDUSER;
    private $username = BD;
    private $password = "ASaQakcXbf9Wh7";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
