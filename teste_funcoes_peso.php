<?php
// Incluir as funções e conectar ao banco de dados
require_once __DIR__ . '/bd/funcoes-bd.php';

// Conectar ao banco
$conexao = conectar();

echo "<h1>Teste das Funções de Peso</h1>";

// 1. Maior peso
$maior = maiorPeso($conexao);
echo "<p><strong>Maior peso:</strong> " . number_format($maior, 2, ',', '.') . " kg</p>";

// 2. Menor peso
$menor = menorPeso($conexao);
echo "<p><strong>Menor peso:</strong> " . number_format($menor, 2, ',', '.') . " kg</p>";

// 3. Peso médio
$medio = pesoMedio($conexao);
echo "<p><strong>Peso médio:</strong> " . number_format($medio, 2, ',', '.') . " kg</p>";

// 4. Pessoas fora do IMC Normal
$pessoasForaNormal = pessoasForaDoImcNormal($conexao);

echo "<h2>Pessoas fora da classificação 'Normal' de IMC</h2>";

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
        echo "<br>IMC: {$imc}";
        echo "</li><br>";
    }
    echo "</ul>";
}

// Desconectar do banco
desconectar($conexao);
?>