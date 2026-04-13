<?php
// Incluir as funções e conectar ao banco de dados
require_once __DIR__ . '/bd/funcoes-bd.php';

// Conectar ao banco
$conexao = conectar();

echo "<h1>Teste Geral das Funções</h1>";

// ==============================================================================
echo "<h2>1. Dados - Índice de Massa Corporal</h2>";
// ==============================================================================

// Qual o IMC médio?
$imc_medio = imcMedio($conexao);
echo "<p><strong>IMC Médio do grupo:</strong> " . number_format($imc_medio, 2, ',', '.') . "</p>";

// Quais os percentuais?
$percentuais = percentual($conexao);
echo "<h3>Percentuais por classificação de IMC:</h3>";
if (!empty($percentuais)) {
    echo "<ul>";
    foreach ($percentuais as $classe => $perc) {
        if ($perc > 0) {
            echo "<li><strong>$classe:</strong> " . number_format($perc, 2, ',', '.') . "%</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>Sem dados para calcular percentuais.</p>";
}

// Qual o IMC de cada participante? Qual a classificação no grau de Obesidade?
$todosParticipantes = imcTodosParticipantes($conexao);
echo "<h3>IMC e Classificação de cada participante:</h3>";
if (empty($todosParticipantes)) {
    echo "<p>Não há pessoas cadastradas.</p>";
} else {
    echo "<ul>";
    foreach ($todosParticipantes as $pessoa) {
        $imc = number_format($pessoa['imc'], 2, ',', '.');
        echo "<li><strong>{$pessoa['nome']}</strong> - IMC: {$imc} | Classificação: <em>{$pessoa['classificacao']}</em></li>";
    }
    echo "</ul>";
}

// ==============================================================================
echo "<h2>2. Dados - Idade</h2>";
// ==============================================================================

// Maior Idade e Nome
$mIdade = maiorIdade($conexao);
$nIdadeMaior = nomeMaior($conexao);
echo "<p><strong>Maior Idade:</strong> {$mIdade} anos (<em>Nome: {$nIdadeMaior}</em>)</p>";

// Menor Idade, Nome e altura
$meIdade = menorIdade($conexao);
$nIdadeMenor = menorNome($conexao);
$altIdadeMenor = menorAltura($conexao);
echo "<p><strong>Menor Idade:</strong> {$meIdade} anos (<em>Nome: {$nIdadeMenor}</em>, altura: " . number_format($altIdadeMenor, 2, ',', '.') . "m)</p>";

// Idade Média
$idMedia = idadeMedia($conexao);
echo "<p><strong>Idade Média do grupo:</strong> " . number_format($idMedia, 2, ',', '.') . " anos</p>";

// Acima e Abaixo da Média
$qtdAcima = quantidadeAcimaMedia($conexao);
$qtdAbaixo = quantidadeAbaixoMedia($conexao);
$nomesAcima = nomesAcimaMedia($conexao);

echo "<p><strong>Qtd pessoas acima da idade média:</strong> {$qtdAcima}</p>";
if (!empty($nomesAcima)) {
    // A função nomesAcimaMedia() retorna os nomes já com ", " no final
    echo "<p><strong>Nome(s) acima da idade média:</strong> " . rtrim(implode("", $nomesAcima), ", ") . "</p>";
}
echo "<p><strong>Qtd pessoas abaixo da idade média:</strong> {$qtdAbaixo}</p>";

// TOP 3 e TOP 5
echo "<h3>Nomes e IMC das três maiores idades:</h3>";
$top3maioresIdades = tresMaioresIdades($conexao);
if (!empty($top3maioresIdades)) {
    echo "<ul>";
    foreach ($top3maioresIdades as $pessoa) {
        $imc = number_format($pessoa['imc'], 2, ',', '.');
        echo "<li><strong>{$pessoa['nome']}</strong>: {$pessoa['idade']} anos | IMC: {$imc}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Sem dados.</p>";
}

echo "<h3>Nomes e IMC das cinco menores idades:</h3>";
$top5menoresIdades = cincoMenoresIdades($conexao);
if (!empty($top5menoresIdades)) {
    echo "<ul>";
    foreach ($top5menoresIdades as $pessoa) {
        $imc = number_format($pessoa['imc'], 2, ',', '.');
        echo "<li><strong>{$pessoa['nome']}</strong>: {$pessoa['idade']} anos | IMC: {$imc}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Sem dados.</p>";
}

// ==============================================================================
echo "<h2>3. Dados - Peso</h2>";
// ==============================================================================

// Maior Peso e Menor Peso
$maiorP = maiorPeso($conexao);
$menorP = menorPeso($conexao);
$medioP = pesoMedio($conexao);

echo "<p><strong>Maior Peso:</strong> " . number_format($maiorP, 2, ',', '.') . " kg</p>";
echo "<p><strong>Menor Peso:</strong> " . number_format($menorP, 2, ',', '.') . " kg</p>";
echo "<p><strong>Peso Médio:</strong> " . number_format($medioP, 2, ',', '.') . " kg</p>";

// Pessoas fora do IMC Normal
echo "<h3>Pessoas fora da classificação 'Normal' de IMC</h3>";
$pessoasForaNormal = pessoasForaDoImcNormal($conexao);

if (empty($pessoasForaNormal)) {
    echo "<p>Não há pessoas ou todas estão com o IMC Normal.</p>";
} else {
    echo "<ul>";
    foreach ($pessoasForaNormal as $pessoa) {
        $acao = $pessoa['acao']; // "ganhar" ou "perder"
        $kg = number_format($pessoa['diferenca_quilos'], 2, ',', '.');
        $imc = number_format($pessoa['imc'], 2, ',', '.');

        echo "<li>";
        echo "<strong>{$pessoa['nome']}</strong><br>";
        echo "Peso Atual: {$pessoa['peso_atual']} kg | Classificação: <em>{$pessoa['classificacao']}</em><br>";
        echo "Ação Necessária: Precisa {$acao} <strong>{$kg} kg</strong> para atingir o peso Normal.";
        echo "</li><br>";
    }
    echo "</ul>";
}

// Desconectar do banco
desconectar($conexao);
?>