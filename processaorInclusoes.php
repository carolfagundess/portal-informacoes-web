<?php

include "funcoes-bd.php";

$auxConexao = conectar();

$auxNome = $_POST["indentificadorNome"];
$auxSobrenome = $_POST["indentificadorSobrenome"];
$auxIdade = $_POST["indentificadorIdade"];
$auxPeso = $_POST["indentificadorPeso"];
$auxAltura = $_POST["indentificadorAltura"];

inserir($auxConexao, $auxNome, $auxSobrenome, $auxIdade, $auxPeso, $auxAltura);