<?php
include "bd/funcoes-bd.php";
$conexao = conectar();

include "html/cabecalho.php";

$maior = maiorPeso($conexao);
$menor = menorPeso($conexao);
$medio = pesoMedio($conexao);
$pessoasForaNormal = pessoasForaDoImcNormal($conexao);
?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4 bg-white">
        
        <!-- Topo com Título e Botão Voltar -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h2 class="text-dark fw-bold mb-0">
                <i class="bi bi-bar-chart-line-fill me-2"></i>Estatísticas de Peso
            </h2>
            <a href="painelAdmin.php" class="btn btn-outline-dark fw-bold">
                <i class="bi bi-arrow-left me-1"></i>Voltar ao Painel
            </a>
        </div>

        <!-- Cards com Resultados Numéricos -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="card bg-light border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-muted text-uppercase mb-3">Maior Peso</h5>
                        <h2 class="fw-bold text-dark mb-0"><?= number_format($maior, 2, ',', '.') ?> <span class="fs-5 text-secondary">kg</span></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-light border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-muted text-uppercase mb-3">Menor Peso</h5>
                        <h2 class="fw-bold text-dark mb-0"><?= number_format($menor, 2, ',', '.') ?> <span class="fs-5 text-secondary">kg</span></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-light border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-muted text-uppercase mb-3">Peso Médio</h5>
                        <h2 class="fw-bold text-dark mb-0"><?= number_format($medio, 2, ',', '.') ?> <span class="fs-5 text-secondary">kg</span></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção da Tabela -->
        <h4 class="text-dark fw-bold mb-4">Pessoas fora da classificação 'Normal' de IMC</h4>
        
        <?php if (empty($pessoasForaNormal)): ?>
            <div class="alert alert-success fs-5 shadow-sm rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Nenhuma pessoa encontrada fora do peso Normal!
            </div>
        <?php else: ?>
            <div class="table-responsive shadow-sm rounded-3">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-3">Nome do Paciente</th>
                            <th class="py-3">Peso Atual</th>
                            <th class="py-3">IMC</th>
                            <th class="py-3">Classificação</th>
                            <th class="py-3">Meta para o Peso Normal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?php foreach ($pessoasForaNormal as $pessoa): ?>
                            <tr>
                                <td class="text-start fw-bold text-capitalize px-4">
                                    <?= htmlspecialchars($pessoa['nome']) ?>
                                </td>
                                <td class="fs-5"><?= number_format($pessoa['peso_atual'], 2, ',', '.') ?> kg</td>
                                <td><?= number_format($pessoa['imc'], 2, ',', '.') ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = ($pessoa['acao'] == 'ganhar') ? 'bg-warning text-dark' : 'bg-danger';
                                    ?>
                                    <span class="badge rounded-pill <?= $badgeClass ?> fs-6 px-3 py-2">
                                        <?= htmlspecialchars($pessoa['classificacao']) ?>
                                    </span>
                                </td>
                                <td>
                                    Precisa <strong class="text-uppercase text-<?= ($pessoa['acao'] == 'ganhar') ? 'warning' : 'danger' ?>"><?= $pessoa['acao'] ?></strong> 
                                    <?= number_format($pessoa['diferenca_quilos'], 2, ',', '.') ?> kg
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<?php 
desconectar($conexao);
include "html/rodape.php"; 
?>
