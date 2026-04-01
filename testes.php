<?php

include "funcoes-bd.php";

$auxConexao = conectar();

$retornoInserir = inserir($auxConexao, "andre", "silva", 46, 100, 1.76);

if ($retornoInserir) {
    echo "Dados Inserido com Sucesso.";
} else {
    echo "Não foi possivel inserir o dado";
}

$retornoExcluir =  excluir($auxConexao, 5); 

if ($retornoExcluir) {
    echo "Dado Excluido com Sucesso.";
} else {
    echo "Não foi possivel excluir o dados";
}

$retornoatualizar = atualizar($auxConexao, 7, "Arthur", "Silva", 30, 80, 1.75);

if ($retornoatualizar) {
    echo "Usuario Atualizado com Sucesso.";
} else {
    echo "Não foi possivel atualizar o usuário";
}

$retornodesconectar = desconectar($auxConexao);

if ($retornodesconectar) {
    echo "Usuario Desconectado com Sucesso.";
} else {
    echo "Não foi possivel desconectar o usuário";
}