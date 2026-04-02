<?php

function conectar(): mysqli
{

    include "conexao-bd.php";

    $conexao = mysqli_connect($localServidor, $usuario, $senha, $nomeBaseDados);
    //Verificando a Conexao com a Base de Dados
    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    return $conexao;
}

function inserir(mysqli $conexao, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    $comandoSQL = "insert into imc (nome,sobrenome,idade,peso,altura) values ('$nome', '$sobrenome', $idade, $peso, $altura)";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    return $retornoBanco;
}

function excluir(mysqli $conexao,int $id) : bool
{
    $comandoSQL = "DELETE from imc WHERE idpessoa = $id";
    if (mysqli_query($conexao, $comandoSQL)) {
        return true;
    } else {
        return false;
    }

}

function atualizar(mysqli $conexao, int $id, string $nome, string $sobrenome,int $idade, float $peso, float $altura): bool
{
    //UPDATE
    $comandoSQL = "UPDATE imc SET nome ='$nome', sobrenome = '$sobrenome', idade = $idade, peso = $peso, altura = $altura WHERE idpessoa = $id" ;
    if (mysqli_query($conexao, $comandoSQL)) {
        return true;
    } else {
        return false;
    }

}

function consultar(mysqli $conexao): array
{
    $comandoSQL = "SELECT * FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $resultados = [];

    if (mysqli_num_rows($retornoBanco) > 0) {
        while ($registro = mysqli_fetch_assoc($retornoBanco)) {
            $resultados[] = $registro;
        }
    }

    return $resultados;
}

function calcularIMC(float $peso, float $altura): float {
    return $peso / ($altura * $altura);
}

function classificarIMC(float $imc): string {
    if ($imc <= 18.5) return "Abaixo do peso";
    elseif ($imc <= 24.9) return "Normal";
    elseif ($imc <= 29.9) return "Sobrepeso";
    elseif ($imc <= 34.9) return "Obesidade I";
    elseif ($imc <= 39.9) return "Obesidade II";
    else return "Obesidade III";
}

function desconectar($conexao)
{
    return mysqli_close($conexao);
}
