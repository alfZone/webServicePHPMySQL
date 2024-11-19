<?php

namespace app;

class ControllerCarros{
	
	//devolve todos os carros
	public function getAll(){
		// Lê o conteúdo do ficheiro JSON
		$json_data = @file_get_contents(_JSON_CARROS);
		// Converte o JSON em um array associativo PHP
		$data = json_decode($json_data, true);
		// Verifica se o ficheiro foi lido corretamente
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
				$resp= "Erro ao decodificar o ficheiro JSON: " . json_last_error_msg();
				echo json_encode(['msg'=>$resp, 'status' => '500']);
		} else {
				$data["carros"][0]['status']='200';
				echo json_encode($data["carros"]);
		}
	}


    public function getById($id){
        // Lê o conteúdo do ficheiro JSON
        $json_data = @file_get_contents(_JSON_CARROS);
        // Converte o JSON em um array associativo PHP
        $data = json_decode($json_data, true);
        // Verifica se o ficheiro foi lido corretamente
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $resp= "Erro ao descodificar o ficheiro JSON: " . json_last_error_msg();
            echo json_encode(['msg'=>$resp, 'status' => '500']);
        } else {
            foreach ($data["carros"] as $item) {
                if (isset($item['id']) && $item['id'] == $id) {
                    $item['status']='200';
                    echo json_encode($item);
                    return;
                }
            }
            echo json_encode(['msg'=>'O id não existe!', 'status' => '500']);
        }
    }

    public function create(){
        // Lê o conteúdo do ficheiro JSON
        $json_data = @file_get_contents(_JSON_CARROS);
        // Converte o JSON em um array associativo PHP
        $data = json_decode($json_data, true);
        // Verifica se o ficheiro foi lido corretamente
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $resp= "Erro ao descodificar o ficheiro JSON: " . json_last_error_msg();
            echo json_encode(['msg'=>$resp, 'status' => '500']);
        } else {
            // Verifica se os dados foram enviados via POST
            if (isset($_POST['id'], $_POST['Marca'], $_POST['Detalhes'], $_POST['Foto'])) {
                // Cria o novo carro com base nos dados recebidos via POST
                $novoCarro = ["id" => $_POST['id'],"Marca" => $_POST['Marca'],
                    "Detalhes" => $_POST['Detalhes'],"Foto" => $_POST['Foto']];  
                // Adiciona o novo carro ao array de "carros"
                $data['carros'][] = $novoCarro;
                // Codifica o array novamente para JSON
                $novoJsonContent = json_encode($data, JSON_PRETTY_PRINT);
     		    // Escreve os dados atualizados de volta no ficheiro
                if (file_put_contents(_JSON_CARROS, $novoJsonContent)) {
                  echo json_encode(['msg'=>'Carro adicionado com sucesso.','status'=>'200']);
                    return;
                } else {
                  echo json_encode(['msg'=>'Erro ao escrever no ficheiro.','status'=>'500']);
                    return;
                }
            } else {
                echo json_encode(['msg'=>"Erro: Dados incompletos. Certifique-se de que os campos 'id', 'Marca', 'Detalhes' e 'Foto' estão presentes.", 'status' => '500']);
            }
		}
	}


    public function update(){
        echo json_encode(['msg'=>'Atualiza o carrinho']);
    }

	public function delete($id){
        echo json_encode(['msg'=>'Apaga o carrinho com o ID:' . $id]);
    }

    public function teste(){
     echo json_encode(['msg'=>'funcionou']);
    }

}
?>
