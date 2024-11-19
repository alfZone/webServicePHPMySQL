<?php
namespace app;
use src\Connection;
use PDO;

//require_once 'Database.php'; // Arquivo de conexão com a base de dados

class ControllerCarros {

    private $conn;
    private $database;

    public function __construct() {
        $this->database = new Connection();
        $this->conn = $this->database->getConnection();
    }

    public function setData($sql, $parameters=""){
        try {
            $stmt = $this->conn->prepare($sql);
            if (!$parameters==""){
                foreach($parameters as (key=>$value)){
                    echo "key=$key<br>";
                    echo "value=$value<br>";

                    $this->database->bindParamAuto($stmt,':'.$key, $value);
                } 
            }
            $stmt->execute();
            //return $stmt->errorInfo();
            return $stmt->debugDumpParams();
        } catch (\PDOException $e) {
            return json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        }
    }



    public function getData($sql, $parameters=""){
        try {
            $stmt = $this->conn->prepare($sql);
            if (!$parameters==""){
                foreach($parameters as $key=>$value){
                    $this->database->bindParamAuto($stmt,':'.$key, $value);
                } 
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
        $p['id']=$id;
        $carro = $this->getData("SELECT * FROM alfCarros WHERE id = :id", $p);
        print_r($carro);
        if ($carro) {
            echo json_encode($carro);
        } else {
            echo json_encode(['msg' => 'Carro não encontrado', 'status' => '404']);
        }
    }

    // Criar um novo carro
    public function create() {
        $p['marca']=$_POST['Marca'];
        $p['detalhes']=$_POST['Detalhes'];
        $p['foto']=$_POST['Foto'];
        $resp = $this->setData("INSERT INTO alfCarros (marca, detalhes, foto) VALUES (:marca, :detalhes, :foto)", $p);
        print_r($resp);
        //try {
            //$query = "INSERT INTO alfCarros (marca, detalhes, foto) VALUES (:marca, :detalhes, :foto)";
            //$stmt = $this->conn->prepare($query);

            //$stmt->bindParam(':marca', $_POST['Marca']);
            //$stmt->bindParam(':detalhes', $_POST['Detalhes']);
            //$stmt->bindParam(':foto', $_POST['Foto']);

            //print_r($_POST);
            //if ($stmt->execute()) {
            //    echo json_encode(['msg' => 'Carro adicionado com sucesso.', 'status' => '200', 'Marca' => $_POST['Marca']]);
            //} else {
            //    echo json_encode(['msg' => 'Erro ao adicionar o carro.', 'status' => '500']);
            //}
        //} catch (\PDOException $e) {
        //    echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        //}
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

