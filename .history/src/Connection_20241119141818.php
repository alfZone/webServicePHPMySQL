<?php
namespace src;
use PDO;

class Connection{
  // put the database stuffs here in that scope
  private $host = _SERVER;
  private $db_name = _BDUSER;
  private $username = _BD;
  private $password = _BDPASS;
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

  function bindParamAuto($stmt, $param, $value) {
    if (is_int($value)) {
        $type = PDO::PARAM_INT;
    } elseif (is_bool($value)) {
        $type = PDO::PARAM_BOOL;
    } elseif (is_null($value)) {
        $type = PDO::PARAM_NULL;
    } else {
        $type = PDO::PARAM_STR;
    }  
    $stmt->bindParam($param, $value, $type);
    }

}