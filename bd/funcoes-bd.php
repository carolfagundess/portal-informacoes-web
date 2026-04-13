<?php
//FUNCOES TIPADAS 
function conectar(): mysqli
{
    include "conexao-bd.php";

    $conexao = mysqli_connect($localServidor, $usuario, $senha, $nomeBaseDados);

    if (!$conexao) {
        registrarLog("ERRO - Conexão falhou: " . mysqli_connect_error());
        die("Conexão falhou");
    }

    registrarLog("SUCESSO - Conexão estabelecida");
    return $conexao;
}

function inserir(mysqli $conexao, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    $comandoSQL = "INSERT INTO imc (nome, sobrenome, idade, peso, altura) 
                   VALUES ('$nome', '$sobrenome', $idade, $peso, $altura)";

    $retornoBanco = mysqli_query($conexao, $comandoSQL);

    if ($retornoBanco) {
        registrarLog("SUCESSO - Inserção: {$nome} {$sobrenome}");
        return true;
    } else {
        registrarLog("ERRO - Inserção: {$nome} {$sobrenome} | Erro: " . mysqli_error($conexao));
        return false;
    }
}

function excluir(mysqli $conexao, int $id): bool
{
    $comandoSQL = "DELETE FROM imc WHERE idpessoa = $id";

    if (mysqli_query($conexao, $comandoSQL)) {
        registrarLog("SUCESSO - Exclusão ID {$id}");
        return true;
    } else {
        registrarLog("ERRO - Exclusão ID {$id} | Erro: " . mysqli_error($conexao));
        return false;
    }
}

function atualizar(mysqli $conexao, int $id, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    $comandoSQL = "UPDATE imc 
                   SET nome='$nome', sobrenome='$sobrenome', idade=$idade, peso=$peso, altura=$altura 
                   WHERE idpessoa=$id";

    if (mysqli_query($conexao, $comandoSQL)) {
        registrarLog("SUCESSO - Atualização ID {$id}");
        return true;
    } else {
        registrarLog("ERRO - Atualização ID {$id} | Erro: " . mysqli_error($conexao));
        return false;
    }
}

function consultar(mysqli $conexao): array
{
    $comandoSQL = "SELECT * FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL);

    if (!$retornoBanco) {
        registrarLog("ERRO - Consulta geral | Erro: " . mysqli_error($conexao));
        return [];
    }

    registrarLog("SUCESSO - Consulta geral executada");

    $resultados = [];

    while ($registro = mysqli_fetch_assoc($retornoBanco)) {
        $resultados[] = $registro;
    }

    return $resultados;
}

function consultarPorId(mysqli $conexao, int $id): ?array
{
    $comandoSQL = "SELECT * FROM imc WHERE idpessoa = $id";
    $retornoBanco = mysqli_query($conexao, $comandoSQL);

    if (!$retornoBanco) {
        registrarLog("ERRO - Consulta ID {$id} | Erro: " . mysqli_error($conexao));
        return null;
    }

    registrarLog("SUCESSO - Consulta ID {$id}");

    return mysqli_num_rows($retornoBanco) > 0 ? mysqli_fetch_assoc($retornoBanco) : null;
}

function calcularIMC(float $peso, float $altura): float
{
    $imc = $peso / ($altura * $altura);
    registrarLog("Cálculo IMC realizado: {$imc}");
    return $imc;
}

function classificarIMC(float $imc): string
{
    if ($imc <= 18.5) {
        registrarLog("Classificação IMC: Abaixo do peso");
        return "Abaixo do peso";
    } elseif ($imc <= 24.9) {
        registrarLog("Classificação IMC: Peso normal");
        return "Normal";
    } elseif ($imc <= 29.9) {
        registrarLog("Classificação IMC: Sobrepeso");
        return "Sobrepeso";
    } elseif ($imc <= 34.9) {
        registrarLog("Classificação IMC: Obesidade I");
        return "Obesidade I";
    } elseif ($imc <= 39.9) {
        registrarLog("Classificação IMC: Obesidade II");
        return "Obesidade II";
    } else {
        registrarLog("Classificação IMC: Obesidade III");
        return "Obesidade III";
    }
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

    registrarLog("Percentual de IMC calculado");
    return $classificacoes;
}
function imcMedio(mysqli $conexao): float
{
    $dados = consultar($conexao);

    $total = count($dados);

    $totalIMC = count($dados);

    $somaIMC = 0;

    for ($i = 0; $i < $total; $i++) {
        $somaIMC += calcularIMC($dados[$i]['peso'], $dados[$i]['altura']);
    }

    $resultado = $totalIMC ? $somaIMC / $totalIMC : 0;
    registrarLog("IMC médio calculado: {$resultado}");
    return $resultado;
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

    registrarLog("Maior idade encontrada: {$maiorIdade}");
    return $maiorIdade;
}

function nomeMaior(mysqli $conexao): string
{

    $idadeMaior = maiorIdade($conexao);

    $comandoSQL = "SELECT nome, sobrenome FROM imc WHERE idade = $idadeMaior LIMIT 1";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $registro = mysqli_fetch_array($retornoBanco);

    $nome = $registro['nome'] . " " . $registro['sobrenome'];
    registrarLog("Nome com maior idade: {$nome}");
    return $nome;

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

    registrarLog("Menor idade encontrada: {$menorIdade}");
    return $menorIdade;

}

function menorAltura(mysqli $conexao): float
{
    $idadeMaisNova = menorIdade($conexao);

    $comandoSQL = "SELECT altura FROM imc WHERE idade = $idadeMaisNova LIMIT 1";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $registro = mysqli_fetch_array($retornoBanco);
    registrarLog("Menor altura encontrada: {$registro['altura']}");
    return $registro['altura'];
}

function menorNome(mysqli $conexao): string
{

    $idadeMaisNova = menorIdade($conexao);

    $comandoSQL = "SELECT nome, sobrenome FROM imc WHERE idade = $idadeMaisNova LIMIT 1";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $registro = mysqli_fetch_array($retornoBanco);

    $nome = $registro['nome'] . " " . $registro['sobrenome'];
    registrarLog("Nome com menor idade: {$nome}");
    return $nome;

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

    registrarLog("Idade média calculada: {$idadeMedia}");
    return $idadeMedia;
}


function nomesAcimaMedia(mysqli $conexao): array
{

    $mediaIdades = idadeMedia($conexao);
    $nomes = [];

    $comandoSQL = "SELECT nome, sobrenome FROM imc WHERE idade > $mediaIdades";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    while ($registro = mysqli_fetch_array($retornoBanco)) {
        $nomes[] = $registro['nome'] . " " . $registro['sobrenome'] . ", ";
    }

    registrarLog("Busca de nomes acima da média realizada");
    return $nomes;
}

function quantidadeAcimaMedia(mysqli $conexao): int
{

    $quantidadeNomes = 0;

    $mediaIdades = idadeMedia($conexao);
    $comandoSQL = "SELECT idpessoa FROM imc WHERE idade > $mediaIdades";

    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    while ($registro = mysqli_fetch_array($retornoBanco)) {
        $quantidadeNomes++;
    }

    registrarLog("Quantidade acima da média: {$quantidadeNomes}");
    return $quantidadeNomes;
}

function quantidadeAbaixoMedia(mysqli $conexao): int
{

    $quantidadeNomes = 0;

    $mediaIdades = idadeMedia($conexao);
    $comandoSQL = "SELECT idpessoa FROM imc WHERE idade < $mediaIdades";

    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    while ($registro = mysqli_fetch_array($retornoBanco)) {
        $quantidadeNomes++;
    }

    registrarLog("Quantidade abaixo da média: {$quantidadeNomes}");
    return $quantidadeNomes;
}

function maiorPeso(mysqli $conexao): float
{
    $comandoSQL = "SELECT MAX(peso) as maior_peso FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    registrarLog("Maior peso: {$registro['maior_peso']}");
    return $registro['maior_peso'] !== null ? (float) $registro['maior_peso'] : 0.0;
}

function menorPeso(mysqli $conexao): float
{
    $comandoSQL = "SELECT MIN(peso) as menor_peso FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    registrarLog("Menor peso: {$registro['menor_peso']}");
    return $registro['menor_peso'] !== null ? $registro['menor_peso'] : 0.0;
}

function pesoMedio(mysqli $conexao): float
{
    $comandoSQL = "SELECT AVG(peso) as peso_medio FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    registrarLog("Peso médio: {$registro['peso_medio']}");
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

    registrarLog("Lista de pessoas fora do IMC normal gerada");
    return $pessoasForaNormal;
}

function tresMaioresIdades(mysqli $conexao): array
{
    $dados = consultar($conexao);

    usort($dados, function ($a, $b) {
        return $b['idade'] <=> $a['idade'];
    });

    $resultado = [];

    for ($i = 0; $i < min(3, count($dados)); $i++) {
        $imc = calcularIMC($dados[$i]['peso'], $dados[$i]['altura']);

        $resultado[] = [
            'nome' => $dados[$i]['nome'] . " " . $dados[$i]['sobrenome'],
            'idade' => $dados[$i]['idade'],
            'imc' => round($imc, 2)
        ];
    }

    registrarLog("Top 3 maiores idades gerado");
    return $resultado;
}

function cincoMenoresIdades(mysqli $conexao): array
{
    $dados = consultar($conexao);

    usort($dados, function ($a, $b) {
        return $a['idade'] <=> $b['idade'];
    });

    $resultado = [];

    for ($i = 0; $i < min(5, count($dados)); $i++) {
        $imc = calcularIMC($dados[$i]['peso'], $dados[$i]['altura']);

        $resultado[] = [
            'nome' => $dados[$i]['nome'] . " " . $dados[$i]['sobrenome'],
            'idade' => $dados[$i]['idade'],
            'imc' => round($imc, 2)
        ];
    }

    registrarLog("Top 5 menores idades gerado");
    return $resultado;
}

function imcTodosParticipantes(mysqli $conexao): array
{
    $dados = consultar($conexao);
    $resultado = [];

    foreach ($dados as $pessoa) {
        $imc = calcularIMC($pessoa['peso'], $pessoa['altura']);
        
        $resultado[] = [
            'nome' => $pessoa['nome'] . " " . $pessoa['sobrenome'],
            'idade' => $pessoa['idade'],
            'peso' => $pessoa['peso'],
            'altura' => $pessoa['altura'],
            'imc' => round($imc, 2),
            'classificacao' => classificarIMC($imc)
        ];
    }

    registrarLog("Lista de IMC de todos os participantes gerada");
    return $resultado;
}

function desconectar($conexao)
{
    registrarLog("Conexão encerrada");

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
