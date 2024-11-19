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
        echo json_encode(['msg'=>'Cria um novo carrinho']);
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
