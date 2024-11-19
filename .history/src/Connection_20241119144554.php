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

  public function getData($sql, $parameters=""){
    try {
        $stmt = $this->conn->prepare($sql);
        if (!$parameters==""){
            foreach($parameters as $key=>$value){
                $this->bindParamAuto($stmt,':'.$key, $value);
            } 
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        return json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
    }
}

public function setData($sql, $parameters=[]){
    try {
        $stmt = $this->conn->prepare($sql);
        if (!empty($parameters)){
            //print_r($parameters);
            foreach($parameters as $key=>$value){
                //echo "key=$key<br>";
                //echo "value=$value<br>";
                $this->bindParamAuto($stmt,':'.$key, $value);
            } 
        }
        $stmt->execute();
        return [
            'status' => '200',
            'msg' => 'Operação realizada com sucesso.',
            'lastInsertId' => $this->conn->lastInsertId(), // ID do último registro inserido
            'query' => $this->isDebug() ? $stmt->debugDumpParams() : null // Debug da consulta se em modo debug
        ];
    } catch (\PDOException $e) {
        return [
            'status' => '500',
            'msg' => 'Erro ao executar a consulta: ' . $e->getMessage(),
            'errorCode' => $e->getCode(),
            'sqlState' => $e->errorInfo[0] ?? null // SQLSTATE se disponível
        ];
    }
}



}