<?php
namespace app;
use src\Connection;

//require_once 'Database.php'; // Arquivo de conexão com a base de dados

class ControllerCarros {

    private $conn;

    public function __construct() {
        $database = new Connection();
        $this->conn = $database->getConnection();
    }

    function bindParamAuto($stmt, $param, &$value) {
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
            if 
            foreach($parameters as $key=>$value){
                $stmt->bindParamAuto($stmt,':'.$key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        }
    }


    // Obter todos os carros
    public function getAll() {
        $carros = $this->getData("SELECT * FROM alfCarros");
        echo json_encode($carros);
    }

    // Obter carro por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM alfCarros WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $carro = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($carro) {
                echo json_encode($carro);
            } else {
                echo json_encode(['msg' => 'Carro não encontrado', 'status' => '404']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        }
    }

    // Criar um novo carro
    public function create() {
        try {
            $query = "INSERT INTO alfCarros (marca, detalhes, foto) VALUES (:marca, :detalhes, :foto)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':marca', $_POST['Marca']);
            $stmt->bindParam(':detalhes', $_POST['Detalhes']);
            $stmt->bindParam(':foto', $_POST['Foto']);

            //print_r($_POST);
            if ($stmt->execute()) {
                echo json_encode(['msg' => 'Carro adicionado com sucesso.', 'status' => '200', 'Marca' => $_POST['Marca']]);
            } else {
                echo json_encode(['msg' => 'Erro ao adicionar o carro.', 'status' => '500']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        }
    }

    // Atualizar um carro
    public function update() {
        try {
            parse_str(file_get_contents("php://input"), $putData);

            $query = "UPDATE alfCarros SET marca = :marca, detalhes = :detalhes, foto = :foto WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $putData['id']);
            $stmt->bindParam(':marca', $putData['Marca']);
            $stmt->bindParam(':detalhes', $putData['Detalhes']);
            $stmt->bindParam(':foto', $putData['Foto']);

            if ($stmt->execute()) {
                echo json_encode(['msg' => 'Carro atualizado com sucesso.', 'status' => '200', 'Marca' => $putData['Marca']]);
            } else {
                echo json_encode(['msg' => 'Erro ao atualizar o carro.', 'status' => '500']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        }
    }

    // Deletar um carro
    public function delete($id) {
        try {
            $query = "DELETE FROM alfCarros WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['msg' => 'Carro deletado com sucesso.', 'status' => '200']);
            } else {
                echo json_encode(['msg' => 'Erro ao deletar o carro.', 'status' => '500']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        }
    }

    // Teste de conexão
    public function teste() {
        echo json_encode(['msg' => 'Conexão funcionando']);
    }
}
?>

