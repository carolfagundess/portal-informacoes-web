<?php
include "bd/funcoes-bd.php";

$idUrl = $_GET["id"] ?? null;
$pessoaUrl = null;

if ($idUrl) {
    $auxConexao = conectar();
    $pessoaUrl = consultarPorId($auxConexao, $idUrl);
    desconectar($auxConexao);
}

$nome = $pessoaUrl['nome'] ?? "";
$sobrenome = $pessoaUrl['sobrenome'] ?? "";
$idade = $pessoaUrl['idade'] ?? "";
$peso = $pessoaUrl['peso'] ?? "";
$altura = $pessoaUrl['altura'] ?? "";

$acao = $pessoaUrl ? "processarAlteracoes.php" : "processarInclusoes.php";

include "html/cabecalho.php";
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white p-3">
                <h4 class="mb-0 text-center fw-bold">Registro de Saúde IMC</h4>
            </div>

            <div class="card-body p-4 bg-white">
                <form action="<?= $acao ?>" method="post">

                    <?php if ($idUrl): ?>
                        <input type="hidden" name="id" value="<?= $idUrl ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Nome:</label>
                        <input type="text" class="form-control form-control-lg" name="indentificadorNome"
                            value="<?= $nome ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Sobrenome:</label>
                        <input type="text" class="form-control form-control-lg" name="indentificadorSobrenome"
                            value="<?= $sobrenome ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Idade:</label>
                        <input type="number" class="form-control form-control-lg" name="indentificadorIdade"
                            value="<?= $idade ?>">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Peso (kg):</label>
                            <input type="text" class="form-control form-control-lg" name="indentificadorPeso"
                                value="<?= $peso ?>" placeholder="Ex: 60.5">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Altura (m):</label>
                            <input type="text" class="form-control form-control-lg" name="indentificadorAltura"
                                value="<?= $altura ?>" placeholder="Ex: 1.70">
                        </div>
                    </div>

                    <div class="d-grid mt-2">
                        <button type="submit"
                            class="btn <?= $idUrl ? 'btn-warning' : 'btn-primary' ?> btn-lg fw-bold shadow-sm">
                            <?= $idUrl ? "Salvar Alterações" : "Gravar Registro" ?>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include "html/rodape.php"; ?>