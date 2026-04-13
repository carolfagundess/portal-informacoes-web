<?php
include "bd/funcoes-bd.php";
$conexao = conectar();

include "html/cabecalho.php";
?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4 bg-white">
        <h2 class="text-center mb-4 text-dark fw-bold">Dados do Índice de Massa Corporal (IMC)</h2>
        
        <?php
        $imc_medio = imcMedio($conexao);
        echo "<p class='fs-5'><strong>IMC Médio do grupo:</strong> " . number_format($imc_medio, 2, ',', '.') . "</p>";

        $percentuais = percentual($conexao);
        echo "<h3 class='mt-4'>Percentuais por classificação de IMC:</h3>";
        if (!empty($percentuais)) {
            echo "<ul class='list-group mb-4 text-dark'>";
            foreach ($percentuais as $classe => $perc) {
                if ($perc > 0) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                    echo "<strong>$classe</strong>";
                    echo "<span class='badge bg-primary rounded-pill'>" . number_format($perc, 2, ',', '.') . "%</span>";
                    echo "</li>";
                }
            }
            echo "</ul>";
        } else {
            echo "<p class='text-muted'>Sem dados para calcular percentuais.</p>";
        }

        $todosParticipantes = imcTodosParticipantes($conexao);
        echo "<h3 class='mt-4'>IMC e Classificação de cada participante:</h3>";
        if (empty($todosParticipantes)) {
            echo "<p class='text-muted'>Não há pessoas cadastradas.</p>";
        } else {
            echo "<ul class='list-group'>";
            foreach ($todosParticipantes as $pessoa) {
                $imc = number_format($pessoa['imc'], 2, ',', '.');
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                echo "<span><i class='bi bi-person-fill text-muted me-2'></i><strong>{$pessoa['nome']}</strong> - IMC: {$imc}</span>";
                echo "<span class='badge bg-secondary'>{$pessoa['classificacao']}</span>";
                echo "</li>";
            }
            echo "</ul>";
        }
        ?>
        <div class="text-center mt-4">
             <a href="painelAdmin.php" class="btn btn-outline-dark px-4 py-2 fw-bold">Voltar ao Painel Admin</a>
        </div>
    </div>
</div>

<?php 
desconectar($conexao);
include "html/rodape.php"; 
?>
