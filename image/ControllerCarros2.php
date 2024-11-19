<?php

namespace app;

class ControllerCarros{
	
	public function getAll(){
		// Lê o conteúdo do ficheiro JSON
		$json_data = @file_get_contents(_JSON_CARROS);
		// Converte o JSON em um array associativo PHP
		$data = json_decode($json_data, true);
		//print_r($data);
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
			$resp= "Erro ao decodificar o ficheiro JSON: " . json_last_error_msg();
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
			$resp= "Erro ao decodificar o ficheiro JSON: " . json_last_error_msg();
			echo json_encode(['msg'=>$resp, 'status' => '500']);
		} else {
			// Verifica se os dados foram enviados via POST
			//print_r($_POST);
			if (isset($_POST['id'], $_POST['Marca'], $_POST['Detalhes'], $_POST['Foto'])) {
				// Cria o novo carro com base nos dados recebidos via POST
				$novoCarro = [
					"id" => $_POST['id'],
					"Marca" => $_POST['Marca'],
					"Detalhes" => $_POST['Detalhes'],
					"Foto" => $_POST['Foto']
				];	
				// Adiciona o novo carro ao array de "carros"
				$data['carros'][] = $novoCarro;
				// Codifica o array novamente para JSON
				$novoJsonContent = json_encode($data, JSON_PRETTY_PRINT);
		
				// Escreve os dados atualizados de volta no ficheiro
				if (file_put_contents(_JSON_CARROS, $novoJsonContent)) {
					echo json_encode(['msg'=>'Carro adicionado com sucesso.', 'status' => '200']);
					return;
				} else {
					echo json_encode(['msg'=>'Erro ao escrever no ficheiro.', 'status' => '500']);
					return;
				}
			} else {
				echo json_encode(['msg'=>"Erro: Dados incompletos. Certifique-se de que os campos 'id', 'Marca', 'Detalhes' e 'Foto' estão presentes.", 'status' => '500']);
			}
			//echo json_encode(['msg'=>'O id não existe!', 'status' => '500']);
		}
		//echo json_encode(['msg'=>'Cria um novo carrinho']);
	}

	public function update(){
		// Lê o conteúdo do ficheiro JSON
		$json_data = @file_get_contents(_JSON_CARROS);
		// Converte o JSON em um array associativo PHP
		$data = json_decode($json_data, true);
		// Verifica se o ficheiro foi lido corretamente
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp= "Erro ao decodificar o ficheiro JSON: " . json_last_error_msg();
			echo json_encode(['msg'=>$resp, 'status' => '500']);
		} else {
			// Verifica se os dados foram enviados via POST

			// Captura os dados da requisição PUT
			$inputData = file_get_contents("php://input");
			//print_r($inputData);
			//$putData = json_decode($inputData, true);
			parse_str($inputData, $putData);

			//print_r($putData);
			if (isset($putData['id'], $putData['Marca'], $putData['Detalhes'], $putData['Foto'])) {
				// Cria o novo carro com base nos dados recebidos via POST
				$id=$putData['id'];
				$carroAtualizado  = [
					"id" => $putData['id'],
					"Marca" => $putData['Marca'],
					"Detalhes" => $putData['Detalhes'],
					"Foto" => $putData['Foto']
				];	
				// Procura o carro pelo ID
				$carroEncontrado = false;
				foreach ($data['carros'] as &$carro) {
					if ($carro['id'] == $id) {
						// Atualiza os detalhes do carro
						$carro = $carroAtualizado;
						$carroEncontrado = true;
						break;
					}
				}
				if ($carroEncontrado) {
					// Codifica o array novamente para JSON
					$novoJsonContent = json_encode($data, JSON_PRETTY_PRINT);
		
					// Escreve os dados atualizados de volta no ficheiro
					if (file_put_contents(_JSON_CARROS, $novoJsonContent)) {
						echo json_encode(['msg'=>'Carro atualizado com sucesso.', 'status' => '200']);
						return;
					} else {
						echo json_encode(['msg'=>'Erro ao escrever no ficheiro.', 'status' => '500']);
						return;
					}
				} else {
					echo json_encode(['msg'=>"Erro: Carro com o ID $id não encontrado.", 'status' => '500']);
					return;
				}
			} else {
				echo json_encode(['msg'=>"Erro: Dados incompletos. Certifique-se de que os campos 'id', 'Marca', 'Detalhes' e 'Foto' estão presentes.", 'status' => '500']);
			}
		}
	}

	public function delete($id){
		// Lê o conteúdo do ficheiro JSON
		$json_data = @file_get_contents(_JSON_CARROS);
		// Converte o JSON em um array associativo PHP
		$data = json_decode($json_data, true);
		// Verifica se o ficheiro foi lido corretamente
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp= "Erro ao decodificar o ficheiro JSON: " . json_last_error_msg();
			echo json_encode(['msg'=>$resp, 'status' => '500']);
		} else {
			// Verifica se os dados foram enviados via POST

			// Captura os dados da requisição PUT
			//$inputData = file_get_contents("php://input");
			//print_r($inputData);
			//$putData = json_decode($inputData, true);
			//parse_str($inputData, $putData);

			//print_r($putData);
			//if (isset($putData['id'], $putData['Marca'], $putData['Detalhes'], $putData['Foto'])) {
				// Cria o novo carro com base nos dados recebidos via POST
				//$id=$putData['id'];
				//$carroAtualizado  = [
				//	"id" => $putData['id'],
				//	"Marca" => $putData['Marca'],
				//	"Detalhes" => $putData['Detalhes'],
				//	"Foto" => $putData['Foto']
				//];	
				// Procura o carro pelo ID
				$carroEncontrado = false;
				foreach ($data['carros'] as $key => $carro) {
					if ($carro['id'] == $id) {
						// Atualiza os detalhes do carro
						unset($data['carros'][$key]);
						$carroEncontrado = true;
						break;
					}
				}
				if ($carroEncontrado) {
					// Codifica o array novamente para JSON
					$novoJsonContent = json_encode($data, JSON_PRETTY_PRINT);
		
					// Escreve os dados atualizados de volta no ficheiro
					if (file_put_contents(_JSON_CARROS, $novoJsonContent)) {
						echo json_encode(['msg'=>'Carro apagado com sucesso.', 'status' => '200']);
						return;
					} else {
						echo json_encode(['msg'=>'Erro ao escrever no ficheiro.', 'status' => '500']);
						return;
					}
				} else {
					echo json_encode(['msg'=>"Erro: Carro com o ID $id não encontrado.", 'status' => '500']);
					return;
				}
			//} else {
			//	echo json_encode(['msg'=>"Erro: Dados incompletos. Certifique-se de que os campos 'id', 'Marca', 'Detalhes' e 'Foto' estão presentes.", 'status' => '500']);
			//}
		}
		//echo json_encode(['msg'=>'Apaga o carrinho com o ID:' . $id]);
	}

	
	public function teste(){
	 echo json_encode(['msg'=>'funcionou']);
	}
}
?>
