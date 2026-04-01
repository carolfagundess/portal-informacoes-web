<?php

function conectar(): mysqli
{

    include "conexao-bd.php";

    $conexao = mysqli_connect($localServidor, $usuario, $senha, $nomeBaseDados);
    //Verificando a Conexao com a Base de Dados
    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    echo "Conectado com sucesso!";

    return $conexao;
}

function inserir(mysqli $conexao, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    $comandoSQL = "insert into imc (nome,sobrenome,idade,peso,altura) values ('$nome', '$sobrenome', $idade, $peso, $altura)";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    echo "Adicionado com sucesso...:";
    return $retornoBanco;
}

function excluir(mysqli $conexao,int $id)
{
    $comandoSQL = "DELETE from imc WHERE idpessoa = $id";
    if (mysqli_query($conexao, $comandoSQL)) {
        return true;
    } else {
        return false;
    }

}

function atualizar(mysqli $conexao, int $id, string $nome, string $sobrenome,int $idade, float $peso, float $altura)
{
    //UPDATE
    $comandoSQL = "UPDATE imc SET nome ='$nome', sobrenome = '$sobrenome', idade = $idade, peso = $peso, altura = $altura WHERE idpessoa = $id" ;
    if (mysqli_query($conexao, $comandoSQL)) {
        return true;
    } else {
        return false;
    }

}

function consultar(mysqli $conexao): void
{
    $comandoSQL = "SELECT * from imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    if (mysqli_num_rows($retornoBanco) > 0) {
        echo 'ID - NOME - SOBRENOME - IDADE - PESO - ALTURA:<br>';
        while ($registro = mysqli_fetch_array($retornoBanco)) {
            echo $registro['idpessoa'] .
                " " . $registro['nome'] .
                " " . $registro['idade'] .
                " " . $registro['altura'] .
                " " . $registro['peso'] . "<br>";
        }
    } else {
        echo "Nenhum resultado.";
    }

    return;

}

function desconectar($conexao)
{
    return mysqli_close($conexao);
}