<?php
include "bd/funcoes-bd.php";

$idUrl = $_GET["id"] ?? null;
$pessoaUrl = null;

if ($idUrl) {
    $auxConexao = conectar();
    $pessoaUrl = consultarPorId($auxConexao, $idUrl);
    desconectar($auxConexao);
}

$nome = $pessoaUrl['nome'] ?? "";
$sobrenome = $pessoaUrl['sobrenome'] ?? "";
$idade = $pessoaUrl['idade'] ?? "";
$peso = $pessoaUrl['peso'] ?? "";
$altura = $pessoaUrl['altura'] ?? "";

$acao = $pessoaUrl ? "processarAlteracoes.php" : "processarInclusoes.php";
?>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="<?= $acao ?>" method="post">

        <!-- SE TIVER UM ID, ESSE CAMPO INVISÍVEL ENVIA PARA O BANCO! -->
        <?php if ($idUrl): ?>
            <input type="hidden" name="id" value="<?= $idUrl ?>">
        <?php endif; ?>

        <label>Nome:</label>
        <input type="text" name="indentificadorNome" value="<?= $nome ?>">
        <br>

        <label>Sobrenome:</label>
        <input type="text" name="indentificadorSobrenome" value="<?= $sobrenome ?>">
        <br>

        <label>Idade:</label>
        <input type="text" name="indentificadorIdade" value="<?= $idade ?>">
        <br>

        <label>Peso:</label>
        <input type="text" name="indentificadorPeso" value="<?= $peso ?>">
        <br>

        <label>Altura:</label>
        <input type="text" name="indentificadorAltura" value="<?= $altura ?>">
        <br>

        <input type="submit" value="<?= $idUrl ? "Alterar" : "Registrar" ?>">
        <br>
    </form>
</body>


</html>