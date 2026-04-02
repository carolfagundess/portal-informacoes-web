<?php
// Arquivo de funções tipadas [cite: 42]

function conectar(): mysqli {
    include "conexao-bd.php";
    $conexao = mysqli_connect($localServidor, $usuario, $senha, $nomeBaseDados);
    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }
    return $conexao;
}

// Função para registrar operações no log 
function registrarLog(string $acao): void {
    $arquivo = 'operacoes_bd.txt';
    $dataHora = date('d/m/Y H:i:s');
    $mensagem = "[$dataHora] Operação realizada: $acao" . PHP_EOL;
    file_put_contents($arquivo, $mensagem, FILE_APPEND);
}

function inserir(mysqli $conexao, string $nome, string $sobrenome, int $idade, float $weight, float $height): bool {
    $sql = "INSERT INTO imc (nome, sobrenome, idade, peso, altura) VALUES ('$nome', '$sobrenome', $idade, $weight, $height)";
    $resultado = mysqli_query($conexao, $sql);
    if ($resultado) {
        registrarLog("INSERIR - Pessoa: $nome $sobrenome"); [cite: 39]
    }
    return $resultado;
}

function excluir(mysqli $conexao, int $id): bool {
    $sql = "DELETE FROM imc WHERE idpessoa = $id";
    $resultado = mysqli_query($conexao, $sql);
    if ($resultado) {
        registrarLog("EXCLUIR - ID: $id"); [cite: 39]
    }
    return $resultado;
}

// Função de consulta adaptada para retornar o objeto de resultados [cite: 57]
function listarRegistros(mysqli $conexao): mysqli_result {
    $sql = "SELECT * FROM imc";
    return mysqli_query($conexao, $sql);
}

function desconectar(mysqli $conexao): bool {
    return mysqli_close($conexao);
}