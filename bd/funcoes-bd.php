<?php
//FUNCOES TIPADAS 
function conectar(): PDO
{
    include "conexao-bd.php";

    try {
        $dsn = "mysql:host=$localServidor;dbname=$nomeBaseDados;charset=utf8mb4";
        $conexao = new PDO($dsn, $usuario, $senha);

        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        registrarLog("SUCESSO - Conexão PDO");
        return $conexao;

    } catch (PDOException $e) {
        registrarLog("ERRO - Conexão: " . $e->getMessage());
        die("Erro na conexão");
    }
}

function inserir(PDO $conexao, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    try {
        $sql = "INSERT INTO imc (nome, sobrenome, idade, peso, altura)
                VALUES (:nome, :sobrenome, :idade, :peso, :altura)";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':altura', $altura);

        $stmt->execute();

        registrarLog("SUCESSO - Inserção");
        return true;

    } catch (PDOException $e) {
        registrarLog("ERRO - Inserção: " . $e->getMessage());
        return false;
    }
}

function excluir(PDO $conexao, int $id): bool
{
    try {
        $sql = "DELETE FROM imc WHERE idpessoa = :id";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount() > 0;

    } catch (PDOException $e) {
        registrarLog("ERRO - Exclusão: " . $e->getMessage());
        return false;
    }
}

function atualizar(PDO $conexao, int $id, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    try {
        $sql = "UPDATE imc 
                SET nome = :nome, sobrenome = :sobrenome, idade = :idade, peso = :peso, altura = :altura
                WHERE idpessoa = :id";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':altura', $altura);

        return $stmt->execute();

    } catch (PDOException $e) {
        registrarLog("ERRO - Update: " . $e->getMessage());
        return false;
    }
}

function consultar(PDO $conexao): array
{
    try {
        $sql = "SELECT * FROM imc";
        $stmt = $conexao->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        registrarLog("ERRO - Consulta: " . $e->getMessage());
        return [];
    }
}


function consultarPorId(PDO $conexao, int $id): ?array
{
    try {
        $sql = "SELECT * FROM imc WHERE idpessoa = :id";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;

    } catch (PDOException $e) {
        return null;
    }
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

function percentual(PDO $conexao): array
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
function imcMedio(PDO $conexao): float
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

function maiorIdade(PDO $conexao): int
{
    $stmt = $conexao->query("SELECT MAX(idade) as maior FROM imc");
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado['maior'] ?? 0;
}

function nomeMaior(PDO $conexao): string
{
    $stmt = $conexao->query("SELECT nome, sobrenome FROM imc ORDER BY idade DESC LIMIT 1");
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    return $registro ? $registro['nome'] . " " . $registro['sobrenome'] : "";
}

function menorIdade(PDO $conexao): int
{
    $stmt = $conexao->query("SELECT MIN(idade) as menor FROM imc");
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado['menor'] ?? 0;
}

function menorAltura(PDO $conexao): float
{
    $idadeMaisNova = menorIdade($conexao);

    $comandoSQL = "SELECT altura FROM imc WHERE idade = $idadeMaisNova LIMIT 1";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $registro = mysqli_fetch_array($retornoBanco);
    registrarLog("Menor altura encontrada: {$registro['altura']}");
    return $registro['altura'];
}

function menorNome(PDO $conexao): string
{

    $idadeMaisNova = menorIdade($conexao);

    $comandoSQL = "SELECT nome, sobrenome FROM imc WHERE idade = $idadeMaisNova LIMIT 1";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));

    $registro = mysqli_fetch_array($retornoBanco);

    $nome = $registro['nome'] . " " . $registro['sobrenome'];
    registrarLog("Nome com menor idade: {$nome}");
    return $nome;

}

function idadeMedia(PDO $conexao): float
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


function nomesAcimaMedia(PDO $conexao): array
{
    $media = idadeMedia($conexao);

    $stmt = $conexao->prepare("SELECT nome, sobrenome FROM imc WHERE idade > :media");
    $stmt->bindParam(':media', $media);
    $stmt->execute();

    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $nomes = [];

    foreach ($dados as $p) {
        $nomes[] = $p['nome'] . " " . $p['sobrenome'];
    }

    return $nomes;
}

function quantidadeAcimaMedia(PDO $conexao): int
{
    $media = idadeMedia($conexao);

    $stmt = $conexao->prepare("SELECT COUNT(*) as total FROM imc WHERE idade > :media");
    $stmt->bindParam(':media', $media);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado['total'] ?? 0;
}

function quantidadeAbaixoMedia(PDO $conexao): int
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

function maiorPeso(PDO $conexao): float
{
    $comandoSQL = "SELECT MAX(peso) as maior_peso FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    registrarLog("Maior peso: {$registro['maior_peso']}");
    return $registro['maior_peso'] !== null ? (float) $registro['maior_peso'] : 0.0;
}

function menorPeso(PDO $conexao): float
{
    $comandoSQL = "SELECT MIN(peso) as menor_peso FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    registrarLog("Menor peso: {$registro['menor_peso']}");
    return $registro['menor_peso'] !== null ? $registro['menor_peso'] : 0.0;
}

function pesoMedio(PDO $conexao): float
{
    $comandoSQL = "SELECT AVG(peso) as peso_medio FROM imc";
    $retornoBanco = mysqli_query($conexao, $comandoSQL) or die(mysqli_error($conexao));
    $registro = mysqli_fetch_assoc($retornoBanco);
    registrarLog("Peso médio: {$registro['peso_medio']}");
    return $registro['peso_medio'] !== null ? $registro['peso_medio'] : 0.0;
}

function pessoasForaDoImcNormal(PDO $conexao): array
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

function tresMaioresIdades(PDO $conexao): array
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

function cincoMenoresIdades(PDO $conexao): array
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

function imcTodosParticipantes(PDO $conexao): array
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
    return null;
}

function registrarLog(string $acao): void
{
    date_default_timezone_set('America/Sao_Paulo');

    $arquivoLog = __DIR__ . '/../log_operacoes.txt';
    $dataHora = date('d/m/Y H:i:s');
    $mensagem = "[$dataHora] - Ação: $acao" . PHP_EOL;
    file_put_contents($arquivoLog, $mensagem, FILE_APPEND);
}

/*

O código a baixo é para ser a versão PDO só não vou conseguir testar 


<?php
// FUNCOES TIPADAS 

function conectar(): PDO
{
    include "conexao-bd.php";

    try {
        $dsn = "mysql:host=$localServidor;dbname=$nomeBaseDados;charset=utf8mb4";
        $conexao = new PDO($dsn, $usuario, $senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        registrarLog("SUCESSO - Conexão PDO");
        return $conexao;

    } catch (PDOException $e) {
        registrarLog("ERRO - Conexão: " . $e->getMessage());
        die("Erro na conexão");
    }
}

function inserir(PDO $conexao, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    try {
        $sql = "INSERT INTO imc (nome, sobrenome, idade, peso, altura)
                VALUES (:nome, :sobrenome, :idade, :peso, :altura)";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':altura', $altura);

        $stmt->execute();

        registrarLog("SUCESSO - Inserção");
        return true;

    } catch (PDOException $e) {
        registrarLog("ERRO - Inserção: " . $e->getMessage());
        return false;
    }
}

function excluir(PDO $conexao, int $id): bool
{
    try {
        $stmt = $conexao->prepare("DELETE FROM imc WHERE idpessoa = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount() > 0;

    } catch (PDOException $e) {
        registrarLog("ERRO - Exclusão: " . $e->getMessage());
        return false;
    }
}

function atualizar(PDO $conexao, int $id, string $nome, string $sobrenome, int $idade, float $peso, float $altura): bool
{
    try {
        $sql = "UPDATE imc 
                SET nome = :nome, sobrenome = :sobrenome, idade = :idade, peso = :peso, altura = :altura
                WHERE idpessoa = :id";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':altura', $altura);

        return $stmt->execute();

    } catch (PDOException $e) {
        registrarLog("ERRO - Update: " . $e->getMessage());
        return false;
    }
}

function consultar(PDO $conexao): array
{
    try {
        $stmt = $conexao->query("SELECT * FROM imc");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function consultarPorId(PDO $conexao, int $id): ?array
{
    try {
        $stmt = $conexao->prepare("SELECT * FROM imc WHERE idpessoa = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        registrarLog("Consulta por ID {$id}");
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    } catch (PDOException $e) {
        return null;
    }
}

function calcularIMC(float $peso, float $altura): float
{
    return $peso / ($altura * $altura);
}

function classificarIMC(float $imc): string
{
    if ($imc <= 18.5) return "Abaixo do peso";
    if ($imc <= 24.9) return "Normal";
    if ($imc <= 29.9) return "Sobrepeso";
    if ($imc <= 34.9) return "Obesidade I";
    if ($imc <= 39.9) return "Obesidade II";
    return "Obesidade III";
}

function percentual(PDO $conexao): array
{
    $dados = consultar($conexao);
    $total = count($dados);

    if ($total == 0) return [];

    $classificacoes = [
        "Abaixo do peso" => 0,
        "Normal" => 0,
        "Sobrepeso" => 0,
        "Obesidade I" => 0,
        "Obesidade II" => 0,
        "Obesidade III" => 0
    ];

    foreach ($dados as $p) {
        $classe = classificarIMC(calcularIMC($p['peso'], $p['altura']));
        $classificacoes[$classe]++;
    }

    foreach ($classificacoes as $k => $v) {
        $classificacoes[$k] = ($v / $total) * 100;
    }

    return $classificacoes;
}

function imcMedio(PDO $conexao): float
{
    $dados = consultar($conexao);
    $total = count($dados);

    if ($total == 0) return 0;

    $soma = 0;
    foreach ($dados as $p) {
        $soma += calcularIMC($p['peso'], $p['altura']);
    }

    return $soma / $total;
}

function maiorIdade(PDO $conexao): int
{
    $r = $conexao->query("SELECT MAX(idade) as v FROM imc")->fetch(PDO::FETCH_ASSOC);
    return $r['v'] ?? 0;
}

function menorIdade(PDO $conexao): int
{
    $r = $conexao->query("SELECT MIN(idade) as v FROM imc")->fetch(PDO::FETCH_ASSOC);
    return $r['v'] ?? 0;
}

function nomeMaior(PDO $conexao): string
{
    $r = $conexao->query("SELECT nome, sobrenome FROM imc ORDER BY idade DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    return $r ? $r['nome']." ".$r['sobrenome'] : "";
}

function menorNome(PDO $conexao): string
{
    $r = $conexao->query("SELECT nome, sobrenome FROM imc ORDER BY idade ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    return $r ? $r['nome']." ".$r['sobrenome'] : "";
}

function menorAltura(PDO $conexao): float
{
    $r = $conexao->query("SELECT altura FROM imc ORDER BY idade ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    return $r['altura'] ?? 0;
}

function idadeMedia(PDO $conexao): float
{
    $r = $conexao->query("SELECT AVG(idade) as v FROM imc")->fetch(PDO::FETCH_ASSOC);
    return $r['v'] ?? 0;
}

function quantidadeAcimaMedia(PDO $conexao): int
{
    $media = idadeMedia($conexao);
    $stmt = $conexao->prepare("SELECT COUNT(*) as v FROM imc WHERE idade > :m");
    $stmt->bindParam(':m', $media);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['v'] ?? 0;
}

function quantidadeAbaixoMedia(PDO $conexao): int
{
    $media = idadeMedia($conexao);
    $stmt = $conexao->prepare("SELECT COUNT(*) as v FROM imc WHERE idade < :m");
    $stmt->bindParam(':m', $media);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['v'] ?? 0;
}

function maiorPeso(PDO $conexao): float
{
    return $conexao->query("SELECT MAX(peso) as v FROM imc")->fetch(PDO::FETCH_ASSOC)['v'] ?? 0;
}

function menorPeso(PDO $conexao): float
{
    return $conexao->query("SELECT MIN(peso) as v FROM imc")->fetch(PDO::FETCH_ASSOC)['v'] ?? 0;
}

function pesoMedio(PDO $conexao): float
{
    return $conexao->query("SELECT AVG(peso) as v FROM imc")->fetch(PDO::FETCH_ASSOC)['v'] ?? 0;
}

function desconectar($conexao)
{
    return null;
}

function registrarLog(string $acao): void
{
    date_default_timezone_set('America/Sao_Paulo');

    $arquivoLog = __DIR__ . '/../log_operacoes.txt';
    $dataHora = date('d/m/Y H:i:s');
    $mensagem = "[$dataHora] - $acao" . PHP_EOL;

    file_put_contents($arquivoLog, $mensagem, FILE_APPEND);
}
*/
