<?php

include "bd/funcoes-bd.php";

$auxConexao = conectar();

$auxNome = $_POST["indentificadorNome"];
$auxSobrenome = $_POST["indentificadorSobrenome"];
$auxIdade = (int) $_POST["indentificadorIdade"];
//Customizando em vírgula por porto
$auxPeso = (float) str_replace(',', '.', $_POST["indentificadorPeso"]);
$auxAltura = (float) str_replace(',', '.', $_POST["indentificadorAltura"]);

inserir($auxConexao, $auxNome, $auxSobrenome, $auxIdade, $auxPeso, $auxAltura);
desconectar($auxConexao);
header("Location: formulario.php");
exit;