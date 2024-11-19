<?php

function adicionarCarro($novoCarro, $arquivo = 'carros.json') {
    $carros = lerCarros($arquivo); // Lê os dados existentes
    $carros[] = $novoCarro; // Adiciona o novo carro ao array
    salvarCarros($carros, $arquivo); // Salva os dados atualizados no arquivo JSON
}

function atualizarCarro($marca, $detalhesAtualizados, $arquivo = 'carros.json') {
    $carros = lerCarros($arquivo);
    foreach ($carros as $key => $carro) {
        if ($carro['Marca'] === $marca) { // Encontra o carro pela marca
            $carros[$key] = array_merge($carro, $detalhesAtualizados); // Atualiza os detalhes
            salvarCarros($carros, $arquivo); // Salva os dados atualizados no arquivo JSON
            return true; // Sucesso
        }
    }
    return false; // Carro não encontrado
}

function deletarCarro($marca, $arquivo = 'carros.json') {
    $carros = lerCarros($arquivo);
    foreach ($carros as $key => $carro) {
        if ($carro['Marca'] === $marca) { // Encontra o carro pela marca
            unset($carros[$key]); // Remove o carro do array
            salvarCarros(array_values($carros), $arquivo); // Salva os dados atualizados
            return true; // Sucesso
        }
    }
    return false; // Carro não encontrado
}


?>