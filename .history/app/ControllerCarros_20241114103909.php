<?php
namespace app;
use app\Database;

//require_once 'Database.php'; // Arquivo de conexão com a base de dados

class ControllerCarros {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obter todos os carros
    public function getAll() {
        try {
            $query = "SELECT * FROM alfcarros";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $carros = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            echo json_encode($carros);
        } catch (\PDOException $e) {
            echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => '500']);
        }
    }

    // Obter carro por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM carros WHERE id = :id";
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
            $query = "INSERT INTO carros (marca, detalhes, foto) VALUES (:marca, :detalhes, :foto)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':marca', $_POST['Marca']);
            $stmt->bindParam(':detalhes', $_POST['Detalhes']);
            $stmt->bindParam(':foto', $_POST['Foto']);

            if ($stmt->execute()) {
                echo json_encode(['msg' => 'Carro adicionado com sucesso.', 'status' => '200']);
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

            $query = "UPDATE carros SET marca = :marca, detalhes = :detalhes, foto = :foto WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $putData['id']);
            $stmt->bindParam(':marca', $putData['Marca']);
            $stmt->bindParam(':detalhes', $putData['Detalhes']);
            $stmt->bindParam(':foto', $putData['Foto']);

            if ($stmt->execute()) {
                echo json_encode(['msg' => 'Carro atualizado com sucesso.', 'status' => '200']);
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
            $query = "DELETE FROM carros WHERE id = :id";
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

