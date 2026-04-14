<?php

include "../bd/funcoes-bd.php";

$auxConexao = conectar();

$auxId = (int) ($_POST["id"] ?? 0);
$auxNome = trim($_POST["indentificadorNome"] ?? "");
$auxSobrenome = trim($_POST["indentificadorSobrenome"] ?? "");
$auxIdade = (int) ($_POST["indentificadorIdade"] ?? 0);
$auxPeso = (float) str_replace(',', '.', $_POST["indentificadorPeso"] ?? "0");
$auxAltura = (float) str_replace(',', '.', $_POST["indentificadorAltura"] ?? "0");

if ($auxId <= 0 || empty($auxNome) || empty($auxSobrenome) || $auxIdade <= 0 || $auxPeso <= 0 || $auxAltura <= 0) {
    desconectar($auxConexao);
    header("Location: ../formulario.php?id={$auxId}&erro=dados_invalidos");
    exit;
}

atualizar($auxConexao, $auxId, $auxNome, $auxSobrenome, $auxIdade, $auxPeso, $auxAltura);

desconectar($auxConexao);
header("Location: ../painelAdmin.php");
exit;