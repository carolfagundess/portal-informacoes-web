<?php
include "bd/funcoes-bd.php";
$conexao = conectar();

include "html/cabecalho.php";
?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4 bg-white">
        <h2 class="text-center mb-4 text-dark fw-bold">Análise de Dados por Idade</h2>
        
        <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase fw-bold" style="font-size:0.8rem;">Estatísticas Gerais</h5>
                        <?php
                        $mIdade = maiorIdade($conexao);
                        $nIdadeMaior = nomeMaior($conexao);
                        echo "<p class='mb-2'><strong>Maior Idade:</strong> {$mIdade} anos (<span class='text-primary fw-medium'>{$nIdadeMaior}</span>)</p>";
                        
                        $meIdade = menorIdade($conexao);
                        $nIdadeMenor = menorNome($conexao);
                        $altIdadeMenor = menorAltura($conexao);
                        echo "<p class='mb-2'><strong>Menor Idade:</strong> {$meIdade} anos (<span class='text-primary fw-medium'>{$nIdadeMenor}</span>, altura: " . number_format($altIdadeMenor, 2, ',', '.') . "m)</p>";
                        
                        $idMedia = idadeMedia($conexao);
                        echo "<p class='mb-0'><strong>Idade Média:</strong> " . number_format($idMedia, 2, ',', '.') . " anos</p>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase fw-bold" style="font-size:0.8rem;">Em Relação à Média</h5>
                        <?php
                        $qtdAcima = quantidadeAcimaMedia($conexao);
                        $qtdAbaixo = quantidadeAbaixoMedia($conexao);
                        $nomesAcima = nomesAcimaMedia($conexao);
                        
                        echo "<p class='mb-2'><strong>Acima da média:</strong> {$qtdAcima} pessoa(s)</p>";
                        if (!empty($nomesAcima)) {
                            echo "<p class='mb-2 small'><strong>Nome(s):</strong> " . rtrim(implode("", $nomesAcima), ", ") . "</p>";
                        }
                        echo "<p class='mb-0'><strong>Abaixo da média:</strong> {$qtdAbaixo} pessoa(s)</p>";
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <h3 class='mt-5 fs-4 text-dark border-bottom pb-2'>Três maiores idades</h3>
        <?php
        $top3maioresIdades = tresMaioresIdades($conexao);
        if (!empty($top3maioresIdades)) {
            echo "<div class='list-group mb-4'>";
            foreach ($top3maioresIdades as $pessoa) {
                $imc = number_format($pessoa['imc'], 2, ',', '.');
                echo "<div class='list-group-item d-flex justify-content-between align-items-center py-3'>";
                echo "<div><h6 class='mb-0'><strong>{$pessoa['nome']}</strong></h6> <small class='text-muted'>{$pessoa['idade']} anos</small></div>";
                echo "<span class='badge bg-dark rounded-pill px-3'>IMC {$imc}</span>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p class='text-muted'>Sem dados.</p>";
        }
        ?>
        
        <h3 class='mt-5 fs-4 text-dark border-bottom pb-2'>Cinco menores idades</h3>
        <?php
        $top5menoresIdades = cincoMenoresIdades($conexao);
        if (!empty($top5menoresIdades)) {
            echo "<div class='list-group'>";
            foreach ($top5menoresIdades as $pessoa) {
                $imc = number_format($pessoa['imc'], 2, ',', '.');
                echo "<div class='list-group-item d-flex justify-content-between align-items-center py-3'>";
                echo "<div><h6 class='mb-0'><strong>{$pessoa['nome']}</strong></h6> <small class='text-muted'>{$pessoa['idade']} anos</small></div>";
                echo "<span class='badge bg-dark rounded-pill px-3'>IMC {$imc}</span>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p class='text-muted'>Sem dados.</p>";
        }
        ?>

        <div class="text-center mt-5">
             <a href="painelAdmin.php" class="btn btn-outline-dark px-4 py-2 fw-bold">Voltar ao Painel Admin</a>
        </div>
    </div>
</div>

<?php 
desconectar($conexao);
include "html/rodape.php"; 
?>
