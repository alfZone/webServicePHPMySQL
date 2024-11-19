<?php

namespace app;

class ControllerCarros{
	public function getAll(){
        echo json_encode(['msg'=>'Todos os carrinhos']);
    }

    public function getById($id,$outro){
        echo json_encode(['msg'=>'O Carrinho com o ID:' . $id]);
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
