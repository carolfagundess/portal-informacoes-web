<?php

include "../bd/funcoes-bd.php";

$auxConexao = conectar();

$auxId = $_GET["id"];

excluir($auxConexao, $auxId);

desconectar($auxConexao);
header("Location: ../painelAdmin.php");
exit;