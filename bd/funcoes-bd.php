<?php
// ARQUIVO INTEGRAL DE FUNÇÕES - PADRÃO PDO (Material Prof. Daniel Assmann)

/**
 * Estabelece conexão com o banco de dados via PDO[cite: 2]
 */
function conectar(): PDO
{
    include "conexao-bd.php";
    try {
        // Instanciação conforme roteiro do professor[cite: 2]
        $dsn = "mysql:host=$localServidor;dbname=$nomeBaseDados;charset=utf8mb4";
        $conexao = new PDO($dsn, $usuario, $senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch (PDOException $e) {
        registrarLog("ERRO CRÍTICO - Conexão: " . $e->getMessage());
        die("Erro ao conectar ao banco de dados.");
    }
}

// --- FUNÇÕES DE ESTATÍSTICA DE PESO (Para dadosPeso.php) ---

function maiorPeso(PDO $conexao): float
{
    $stmt = $conexao->query("SELECT MAX(peso) FROM imc");
    return (float) $stmt->fetchColumn();
}

function menorPeso(PDO $conexao): float
{
    $stmt = $conexao->query("SELECT MIN(peso) FROM imc");
    return (float) $stmt->fetchColumn();
}

function pesoMedio(PDO $conexao): float
{
    $stmt = $conexao->query("SELECT AVG(peso) FROM imc");
    return (float) $stmt->fetchColumn();
}

function pessoasForaDoImcNormal(PDO $conexao): array
{
    // Retorna detalhes de quem não está entre 18.5 e 24.9
    $sql = "SELECT nome, sobrenome, peso, altura, (peso / (altura * altura)) AS imc FROM imc WHERE (peso / (altura * altura)) < 18.5 OR (peso / (altura * altura)) > 24.9";
    $stmt = $conexao->query($sql);
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $lista = [];
    foreach ($dados as $linha) {
        $imc = (float) $linha['imc'];
        $classificacao = classificarImc($imc);
        // Calcular peso ideal para IMC 22 (meio da faixa normal)
        $alturaM = (float) $linha['altura'];
        $pesoAtual = (float) $linha['peso'];
        $pesoIdeal = 22 * ($alturaM * $alturaM);
        $diferenca = abs($pesoAtual - $pesoIdeal);
        $acao = ($pesoAtual > $pesoIdeal) ? "perder" : "ganhar";
        $lista[] = [
            'nome' => $linha['nome'] . " " . $linha['sobrenome'],
            'peso_atual' => number_format($pesoAtual, 2, ',', '.'),
            'classificacao' => $classificacao,
            'imc' => $imc,
            'diferenca_quilos' => $diferenca,
            'acao' => $acao
        ];
    }
    return $lista;
}

/**
 * Classifica o IMC em categorias
 */
function classificarImc(float $imc): string
{
    if ($imc < 18.5) return "Abaixo do peso";
    if ($imc <= 24.9) return "Normal";
    if ($imc <= 29.9) return "Sobrepeso";
    if ($imc <= 34.9) return "Obesidade I";
    if ($imc <= 39.9) return "Obesidade II";
    return "Obesidade III";
}

// --- FUNÇÕES DE ESTATÍSTICA DE IDADE (Para dadosIdade.php) ---

function maiorIdade(PDO $conexao): int
{
    $stmt = $conexao->query("SELECT MAX(idade) FROM imc");
    return (int) $stmt->fetchColumn();
}

function menorIdade(PDO $conexao): int
{
    $stmt = $conexao->query("SELECT MIN(idade) FROM imc");
    return (int) $stmt->fetchColumn();
}

function idadeMedia(PDO $conexao): float
{
    $stmt = $conexao->query("SELECT AVG(idade) FROM imc");
    return (float) $stmt->fetchColumn();
}

function nomesAcimaMedia(PDO $conexao): array
{
    $media = idadeMedia($conexao);
    $stmt = $conexao->prepare("SELECT nome, sobrenome FROM imc WHERE idade > :media");
    $stmt->bindParam(':media', $media);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna array para o foreach[cite: 2]
}

function quantidadeAcimaMedia(PDO $conexao): int
{
    $media = idadeMedia($conexao);
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM imc WHERE idade > :media");
    $stmt->bindParam(':media', $media);
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

function quantidadeAbaixoMedia(PDO $conexao): int
{
    $media = idadeMedia($conexao);
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM imc WHERE idade < :media");
    $stmt->bindParam(':media', $media);
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

function nomeMaior(PDO $conexao): string
{
    $stmt = $conexao->query("SELECT CONCAT(nome, ' ', sobrenome) FROM imc ORDER BY idade DESC LIMIT 1");
    return (string) $stmt->fetchColumn();
}

function menorNome(PDO $conexao): string
{
    $stmt = $conexao->query("SELECT CONCAT(nome, ' ', sobrenome) FROM imc ORDER BY idade ASC LIMIT 1");
    return (string) $stmt->fetchColumn();
}

function menorAltura(PDO $conexao): float
{
    $stmt = $conexao->query("SELECT altura FROM imc ORDER BY idade ASC LIMIT 1");
    return (float) $stmt->fetchColumn();
}

function tresMaioresIdades(PDO $conexao): array
{
    $stmt = $conexao->query("SELECT nome, sobrenome, idade, (peso / (altura * altura)) AS imc FROM imc ORDER BY idade DESC LIMIT 3");
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $lista = [];
    foreach ($dados as $linha) {
        $lista[] = [
            'nome' => $linha['nome'] . " " . $linha['sobrenome'],
            'idade' => $linha['idade'],
            'imc' => (float) $linha['imc']
        ];
    }
    return $lista;
}

function cincoMenoresIdades(PDO $conexao): array
{
    $stmt = $conexao->query("SELECT nome, sobrenome, idade, (peso / (altura * altura)) AS imc FROM imc ORDER BY idade ASC LIMIT 5");
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $lista = [];
    foreach ($dados as $linha) {
        $lista[] = [
            'nome' => $linha['nome'] . " " . $linha['sobrenome'],
            'idade' => $linha['idade'],
            'imc' => (float) $linha['imc']
        ];
    }
    return $lista;
}

// --- FUNÇÕES DE ESTATÍSTICA DE IMC (Para dadosImc.php)[cite: 1] ---

function imcMedio(PDO $conexao): float
{
    $stmt = $conexao->query("SELECT AVG(peso / (altura * altura)) FROM imc");
    return (float) $stmt->fetchColumn();
}

/**
 * Retorna todos os participantes e seus IMCs calculados[cite: 1]
 */
function imcTodosParticipantes(PDO $conexao): array
{
    $stmt = $conexao->query("SELECT nome, sobrenome, peso, altura FROM imc");
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $lista = [];
    foreach ($dados as $linha) {
        $imc = (float) $linha['peso'] / ((float) $linha['altura'] * (float) $linha['altura']);
        $lista[] = [
            'nome' => $linha['nome'] . " " . $linha['sobrenome'],
            'imc' => $imc,
            'classificacao' => classificarImc($imc)
        ];
    }
    return $lista;
}

/**
 * Retorna um array com os percentuais de cada categoria para o foreach[cite: 2]
 */
function obterTodosPercentuais(PDO $conexao): array
{
    $categorias = ["Abaixo do peso", "Normal", "Sobrepeso", "Obesidade I", "Obesidade II", "Obesidade III"];
    $resultado = [];
    foreach ($categorias as $cat) {
        $resultado[$cat] = percentual($conexao, $cat);
    }
    return $resultado;
}

function percentual(PDO $conexao, string $situacao = "Normal"): float
{
    $total = (int) $conexao->query("SELECT COUNT(*) FROM imc")->fetchColumn();
    if ($total === 0) return 0;

    $sql = "SELECT COUNT(*) FROM imc WHERE ";
    switch ($situacao) {
        case "Abaixo do peso": $sql .= "(peso / (altura * altura)) < 18.5"; break;
        case "Sobrepeso":      $sql .= "(peso / (altura * altura)) > 24.9"; break;
        case "Normal":         $sql .= "(peso / (altura * altura)) BETWEEN 18.5 AND 24.9"; break;
        default:               $sql .= "(peso / (altura * altura)) > 29.9"; break; // Genérico para Obesidade
    }
    
    $qtd = (int) $conexao->query($sql)->fetchColumn();
    return ($qtd / $total) * 100;
}

// --- FUNÇÕES CRUD (Create, Read, Update, Delete) ---

/**
 * Insere um novo registro na tabela imc
 * Padrão: prepare() + bindParam() + execute()
 */
function inserir(PDO $conexao, string $nome, string $sobrenome, int $idade, float $peso, float $altura): void
{
    $sql = "INSERT INTO imc (nome, sobrenome, idade, peso, altura) VALUES (:nome, :sobrenome, :idade, :peso, :altura)";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':sobrenome', $sobrenome);
    $stmt->bindParam(':idade', $idade);
    $stmt->bindParam(':peso', $peso);
    $stmt->bindParam(':altura', $altura);
    $stmt->execute();
    registrarLog("INSERT - Nome: $nome $sobrenome, Idade: $idade, Peso: $peso, Altura: $altura");
}

/**
 * Consulta um registro específico pelo ID
 * Padrão: prepare() + bindParam() + execute() + fetch()
 */
function consultarPorId(PDO $conexao, int $id): ?array
{
    $sql = "SELECT * FROM imc WHERE idpessoa = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ?: null;
}

/**
 * Atualiza um registro existente na tabela imc
 * Padrão: prepare() + bindParam() + execute()
 */
function atualizar(PDO $conexao, int $id, string $nome, string $sobrenome, int $idade, float $peso, float $altura): void
{
    $sql = "UPDATE imc SET nome = :nome, sobrenome = :sobrenome, idade = :idade, peso = :peso, altura = :altura WHERE idpessoa = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':sobrenome', $sobrenome);
    $stmt->bindParam(':idade', $idade);
    $stmt->bindParam(':peso', $peso);
    $stmt->bindParam(':altura', $altura);
    $stmt->execute();
    registrarLog("UPDATE - ID: $id, Nome: $nome $sobrenome, Idade: $idade, Peso: $peso, Altura: $altura");
}

/**
 * Exclui um registro da tabela imc pelo ID
 * Padrão: prepare() + bindParam() + execute()
 */
function excluir(PDO $conexao, int $id): void
{
    $sql = "DELETE FROM imc WHERE idpessoa = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    registrarLog("DELETE - ID: $id");
}

// --- FUNÇÕES DE APOIO E LOG ---

function registrarLog(string $acao): void
{
    $arquivoLog = __DIR__ . '/../log_operacoes.txt';
    $msg = "[" . date('d/m/Y H:i:s') . "] Ação: $acao" . PHP_EOL;
    file_put_contents($arquivoLog, $msg, FILE_APPEND);
}

function consultar(PDO $conexao): array
{
    $stmt = $conexao->query("SELECT * FROM imc");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Encerra a conexão PDO (seta a variável como null)
 */
function desconectar(?PDO &$conexao): void
{
    $conexao = null;
}
?>