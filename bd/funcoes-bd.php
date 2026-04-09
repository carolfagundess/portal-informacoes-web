<?php
//FUNCOES TIPADAS 
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

    if ($retornoBanco) {
        registrarLog("Inclusão: Registro criado para {$nome} {$sobrenome}.");
    }

    return $retornoBanco;
}

function excluir(mysqli $conexao, int $id): bool
{
    $comandoSQL = "DELETE from imc WHERE idpessoa = $id";
    if (mysqli_query($conexao, $comandoSQL)) {
        registrarLog("Exclusão: Registro com ID {$id} foi excluído.");
        return true;
    } else {
        return false;
    }

}

function atualizar(mysqli $conexao, int $id, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    //UPDATE
    $comandoSQL = "UPDATE imc SET nome ='$nome', sobrenome = '$sobrenome', idade = $idade, peso = $peso, altura = $altura WHERE idpessoa = $id";
    if (mysqli_query($conexao, $comandoSQL)) {
        registrarLog("Atualização: Registro com ID {$id} atualizado para {$nome} {$sobrenome}.");
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

function consultarPorId(mysqli $conexao, int $id): ?array
{
    $comandoSQL = "SELECT * FROM imc WHERE idpessoa = $id";
    $retornoBanco = mysqli_query($conexao, $comandoSQL);

    if (mysqli_num_rows($retornoBanco) > 0) {
        return mysqli_fetch_assoc($retornoBanco);
    }
    return null;
}


function calcularIMC(float $peso, float $altura): float
{
    return $peso / ($altura * $altura);
}

function classificarIMC(float $imc): string
{
    if ($imc <= 18.5)
        return "Abaixo do peso";
    elseif ($imc <= 24.9)
        return "Normal";
    elseif ($imc <= 29.9)
        return "Sobrepeso";
    elseif ($imc <= 34.9)
        return "Obesidade I";
    elseif ($imc <= 39.9)
        return "Obesidade II";
    else
        return "Obesidade III";
}

function percentual(mysqli $conexao): array
{
    $dados = consultar($conexao);

    $total = count($dados);

    if ($total == 0) {
        return [];
    }

    $classificacoes = [
        "Abaixo do peso" => 0,
        "Normal" => 0,
        "Sobrepeso" => 0,
        "Obesidade I" => 0,
        "Obesidade II" => 0,
        "Obesidade III" => 0
    ];


    for ($i = 0; $i < $total; $i++) {
        $imc = calcularIMC($dados[$i]['peso'], $dados[$i]['altura']);
        $classe = classificarIMC($imc);

        $classificacoes[$classe]++;
    }

    $chaves = array_keys($classificacoes);
    $qtdClasses = count($chaves);

    for ($i = 0; $i < $qtdClasses; $i++) {
        $classe = $chaves[$i];
        $classificacoes[$classe] = ($classificacoes[$classe] / $total) * 100;
    }

    return $classificacoes;
}
function imcMedio(mysqli $conexao): float
{
    $dados = consultar($conexao);

    $total = count($dados);

    $totalIMC = count($dados);

    for ($i = 0; $i < $total; $i++) {
        $somaIMC += calcularIMC($dados[$i]['peso'], $dados[$i]['altura']);
    }

    return $totalIMC ? $somaIMC / $totalIMC : 0;
}

function maiorIdade(mysqli $conexao): int
{

    $comandoSQL = "SELECT * FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $maiorIdade = 0;

    while ($registro = mysqli_fetch_array($retornoBanco)) {

        $idadeAtual = $registro['idade'];

        if ($idadeAtual > $maiorIdade) {
            $maiorIdade = $idadeAtual;
        }

    }

    return $maiorIdade;

}

function nomeMaior(mysqli $conexao): string
{

    $idadeMaior = maiorIdade($conexao);

    $comandoSQL = "SELECT nome, sobrenome FROM imc WHERE idade = $idadeMaior LIMIT 1";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $registro = mysqli_fetch_array($retornoBanco);

    return $registro['nome'] . " " . $registro['sobrenome'];

}


function menorIdade(mysqli $conexao): int
{

    $comandoSQL = "SELECT * FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $menorIdade = 999;

    while ($registro = mysqli_fetch_array($retornoBanco)) {

        $idadeAtual = $registro['idade'];

        if ($idadeAtual < $menorIdade) {
            $menorIdade = $idadeAtual;
        }

    }

    return $menorIdade;

}

function menorAltura(mysqli $conexao): float
{
    $idadeMaisNova = menorNome($conexao);

    $comandoSQL = "SELECT altura FROM imc WHERE idade = $idadeMaisNova LIMIT 1";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $registro = mysqli_fetch_array($retornoBanco);
    return $registro['altura'];
}

function menorNome(mysqli $conexao): string
{

    $idadeMaisNova = menorIdade($conexao);

    $comandoSQL = "SELECT nome, sobrenome FROM imc WHERE idade = $idadeMaisNova LIMIT 1";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $registro = mysqli_fetch_array($retornoBanco);

    return $registro['nome'] . " " . $registro['sobrenome'];

}

function idadeMedia(mysqli $conexao): float
{

    $dados = consultar($conexao);

    $totalIdades = count($dados);
    $somaIdades = 0;
    $idadeMedia = 0;


    for ($i = 0; $i < $totalIdades; $i++) {
        $somaIdades += $dados[$i]['idade'];

    }

    $idadeMedia = $totalIdades ? $somaIdades / $totalIdades : 0;

    return $idadeMedia;
}


function nomesAcimaMedia(mysqli $conexao): array
{

    $mediaIdades = idadeMedia($conexao);

    $comandoSQL = "SELECT nome, sobrenome FROM imc WHERE idade > $mediaIdades";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    while ($registro = mysqli_fetch_array($retornoBanco)) {
        $nomes[] = $registro['nome'] . " " . $registro['sobrenome'] . ", ";
    }

    return $nomes;
}

function quantidadeAcimaMedia(mysqli $conexao): int
{

    $quantidadeNomes = 0;

    $mediaIdades = idadeMedia($conexao);
    $comandoSQL = "SELECT id FROM imc WHERE idade > $mediaIdades";

    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    while ($registro = mysqli_fetch_array($retornoBanco)) {
        $quantidadeNomes++;
    }

    return $quantidadeNomes;
}

function quantidadeAbaixoMedia(mysqli $conexao): int
{

    $quantidadeNomes = 0;

    $mediaIdades = idadeMedia($conexao);
    $comandoSQL = "SELECT id FROM imc WHERE idade < $mediaIdades";

    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    while ($registro = mysqli_fetch_array($retornoBanco)) {
        $quantidadeNomes++;
    }

    return $quantidadeNomes;
}

function maiorPeso(mysqli $conexao): float
{
    $comandoSQL = "SELECT MAX(peso) as maior_peso FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    return $registro['maior_peso'] !== null ? (float) $registro['maior_peso'] : 0.0;
}

function menorPeso(mysqli $conexao): float
{
    $comandoSQL = "SELECT MIN(peso) as menor_peso FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    return $registro['menor_peso'] !== null ? $registro['menor_peso'] : 0.0;
}

function pesoMedio(mysqli $conexao): float
{
    $comandoSQL = "SELECT AVG(peso) as peso_medio FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    return $registro['peso_medio'] !== null ? $registro['peso_medio'] : 0.0;
}

function pessoasForaDoImcNormal(mysqli $conexao): array
{
    $dados = consultar($conexao);
    $pessoasForaNormal = [];

    foreach ($dados as $pessoa) {
        $imc = calcularIMC($pessoa['peso'], $pessoa['altura']);
        $classe = classificarIMC($imc);

        if ($classe !== "Normal") {
            $pesoAtual = $pessoa['peso'];
            $altura = $pessoa['altura'];
            $diferenca = 0;
            $acao = "";

            if ($imc <= 18.5) {
                $pesoIdeal = 18.51 * ($altura * $altura);
                $diferenca = $pesoIdeal - $pesoAtual;
                $acao = "ganhar";
            } else {
                $pesoIdeal = 24.9 * ($altura * $altura);
                $diferenca = $pesoAtual - $pesoIdeal;
                $acao = "perder";
            }

            $pessoasForaNormal[] = [
                'nome' => $pessoa['nome'] . " " . $pessoa['sobrenome'],
                'peso_atual' => $pesoAtual,
                'classificacao' => $classe,
                'acao' => $acao,
                'diferenca_quilos' => $diferenca,
                'imc' => $imc
            ];
        }
    }

    return $pessoasForaNormal;
}

function desconectar($conexao)
{
    return mysqli_close($conexao);
}

function registrarLog(string $acao): void
{
    date_default_timezone_set('America/Sao_Paulo');

    $arquivoLog = __DIR__ . '/../log_operacoes.txt';
    $dataHora = date('d/m/Y H:i:s');
    $mensagem = "[$dataHora] - Ação: $acao" . PHP_EOL;
    file_put_contents($arquivoLog, $mensagem, FILE_APPEND);
}