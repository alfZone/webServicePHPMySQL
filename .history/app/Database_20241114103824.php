<?php
namespace app;
use PDO;

// db.php
class Database {
    private $host = "localhost";
    private $db_name = "turma12r";
    private $username = "turma12r";
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
