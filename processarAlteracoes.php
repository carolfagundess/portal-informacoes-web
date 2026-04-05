<?php

include "bd/funcoes-bd.php";

$auxConexao = conectar();

$auxId = (int) $_POST["id"];
$auxNome = $_POST["indentificadorNome"];
$auxSobrenome = $_POST["indentificadorSobrenome"];
$auxIdade = (int) $_POST["indentificadorIdade"];
$auxPeso = (float) str_replace(',', '.', $_POST["indentificadorPeso"]);
$auxAltura = (float) str_replace(',', '.', $_POST["indentificadorAltura"]);

atualizar($auxConexao, $auxId, $auxNome, $auxSobrenome, $auxIdade, $auxPeso, $auxAltura);

desconectar($auxConexao);
header("Location: painel-admin.php");
exit;