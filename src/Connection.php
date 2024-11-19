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

        // Vincula os parâmetros, se fornecidos
        if (!empty($parameters)) {
            foreach ($parameters as $key => $value) {
                $this->bindParamAuto($stmt, ':' . $key, $value);
            }
        }

        // Executa a consulta
        $stmt->execute();

        // Obtém o número de linhas afetadas
        $affectedRows = $stmt->rowCount();

        // Verifica o tipo de operação (baseado no comando SQL)
        $operation = strtoupper(explode(' ', trim($sql))[0]);
        $response = ['status' => '200', 'operation' => $operation];

        switch ($operation) {
            case 'INSERT':
                // Para INSERT, podemos retornar o último ID inserido (se for relevante)
                $response['msg'] = 'Registro inserido com sucesso.';
                $response['lastInsertId'] = $this->conn->lastInsertId();
                break;

            case 'UPDATE':
                $response['msg'] = $affectedRows > 0 
                    ? "{$affectedRows} linha(s) atualizada(s) com sucesso."
                    : "Nenhuma linha foi atualizada. Verifique as condições.";
                break;

            case 'DELETE':
                $response['msg'] = $affectedRows > 0 
                    ? "{$affectedRows} linha(s) apagadas com sucesso."
                    : "Nenhuma linha foi apagada. Verifique as condições.";
                break;

            default:
                $response['msg'] = 'Operação executada com sucesso.';
        }

        return $response;
    } catch (\PDOException $e) {
        // Tratamento de erros
        return [
            'status' => '500',
            'msg' => 'Erro: ' . $e->getMessage()
        ];
    }
}


/**
 * Método auxiliar para verificar se está em modo debug
 * Retorna true para exibir informações de depuração
 */
private function isDebug() {
    return defined('DEBUG') && DEBUG === true;
}


}