<?php
include "bd/funcoes-bd.php";
$auxConexao = conectar();

include "html/cabecalho.php";
?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4 bg-white">
        <h2 class="text-center mb-4 text-dark fw-bold">Painel Administrativo</h2>
        <div class="d-grid gap-3 col-12 col-md-6 mx-auto" style="min-height: 40vh;">
            <a href="tabelaRegistros.php" target="_blank"
                class="btn btn-dark fw-bold fs-5 shadow-sm d-flex justify-content-center align-items-center">Visualizar
                Registros</a>
            <a href="#" target="_blank"
                class="btn btn-dark fw-bold fs-5 shadow-sm d-flex justify-content-center align-items-center">Dados
                IMC</a>
            <a href="#" target="_blank"
                class="btn btn-dark fw-bold fs-5 shadow-sm d-flex justify-content-center align-items-center">Dados
                Idade</a>
            <a href="#" target="_blank"
                class="btn btn-dark fw-bold fs-5 shadow-sm d-flex justify-content-center align-items-center">Dados
                Peso</a>
        </div>
    </div>
</div>

<?php include "html/rodape.php"; ?>