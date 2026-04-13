<?php
include "bd/funcoes-bd.php";
$conexao = conectar();

include "html/cabecalho.php";
?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4 bg-white">
        <h2 class="text-center mb-4 text-dark fw-bold">Análise de Dados de Peso</h2>
        
        <div class="text-center mb-5 mt-4">
            <?php
            $maiorP = maiorPeso($conexao);
            $menorP = menorPeso($conexao);
            $medioP = pesoMedio($conexao);
            ?>
            <div class="row g-3 justify-content-center">
                <div class="col-sm-4">
                    <div class="p-3 border bg-light rounded text-center shadow-sm">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Maior Peso</small>
                        <span class="fs-4 fw-bold text-dark"><?= number_format($maiorP, 2, ',', '.') ?> <small class="fs-6">kg</small></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="p-3 border bg-light rounded text-center shadow-sm">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Menor Peso</small>
                        <span class="fs-4 fw-bold text-dark"><?= number_format($menorP, 2, ',', '.') ?> <small class="fs-6">kg</small></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="p-3 border bg-light rounded text-center shadow-sm">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Peso Médio</small>
                        <span class="fs-4 fw-bold text-dark"><?= number_format($medioP, 2, ',', '.') ?> <small class="fs-6">kg</small></span>
                    </div>
                </div>
            </div>
        </div>

        <h3 class='mt-4 text-warning border-bottom pb-2'><i class="bi bi-exclamation-triangle-fill"></i> Pessoas fora do IMC Normal</h3>
        <?php
        $pessoasForaNormal = pessoasForaDoImcNormal($conexao);

        if (empty($pessoasForaNormal)) {
            echo "<div class='alert alert-success mt-3' role='alert'><strong>Excelente!</strong> Não há pessoas cadastradas ou todas estão com o peso classificado como Normal.</div>";
        } else {
            echo "<div class='row row-cols-1 row-cols-md-2 g-4 mt-2'>";
            foreach ($pessoasForaNormal as $pessoa) {
                $acao = $pessoa['acao']; // "ganhar" ou "perder"
                $kg = number_format($pessoa['diferenca_quilos'], 2, ',', '.');
                $imc = number_format($pessoa['imc'], 2, ',', '.');
                
                $corAcao = ($acao == "perder") ? "text-danger" : "text-primary";
                $borderCor = ($acao == "perder") ? "border-danger" : "border-primary";

                echo "<div class='col'>";
                echo "<div class='card h-100 shadow-sm border-0 border-start border-4 {$borderCor}'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title fw-bold text-dark mb-3'>{$pessoa['nome']}</h5>";
                
                echo "<div class='d-flex justify-content-between mb-2'>";
                echo "<span class='text-muted'>Peso Atual:</span> <strong>{$pessoa['peso_atual']} kg</strong>";
                echo "</div>";
                
                echo "<div class='d-flex justify-content-between mb-2'>";
                echo "<span class='text-muted'>Classificação:</span> <span class='badge bg-secondary'>{$pessoa['classificacao']}</span>";
                echo "</div>";
                
                echo "<div class='d-flex justify-content-between mb-3'>";
                echo "<span class='text-muted'>IMC:</span> <strong>{$imc}</strong>";
                echo "</div>";
                
                echo "<div class='alert alert-secundary bg-light border-0 mb-0' role='alert'>";
                echo "<p class='card-text mb-0 fs-6 text-center text-dark'>Meta para IMC Normal: <br> <span class='{$corAcao} text-uppercase fw-bold'>{$acao}</span> <strong class='fs-5'>{$kg} kg</strong></p>";
                echo "</div>";
                
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
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
