<?php

include "../bd/funcoes-bd.php";

$auxConexao = conectar();

$auxNome = trim($_POST["indentificadorNome"] ?? "");
$auxSobrenome = trim($_POST["indentificadorSobrenome"] ?? "");
$auxIdade = (int) ($_POST["indentificadorIdade"] ?? 0);
//Customizando em vírgula por porto
$auxPeso = (float) str_replace(',', '.', $_POST["indentificadorPeso"] ?? "0");
$auxAltura = (float) str_replace(',', '.', $_POST["indentificadorAltura"] ?? "0");

if (empty($auxNome) || empty($auxSobrenome) || $auxIdade <= 0 || $auxPeso <= 0 || $auxAltura <= 0) {
    desconectar($auxConexao);
    header("Location: ../formulario.php?erro=dados_invalidos");
    exit;
}

inserir($auxConexao, $auxNome, $auxSobrenome, $auxIdade, $auxPeso, $auxAltura);
desconectar($auxConexao);
header("Location: ../formulario.php");
exit;