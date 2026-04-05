<?php
include "bd/funcoes-bd.php";
$auxConexao = conectar();
$lista = consultar($auxConexao);

include "html/cabecalho.php";
?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4 bg-white">
        <h2 class="text-center mb-4 text-dark fw-bold">Gestão de Pacientes da OMS</h2>

        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped align-middle">

                <thead class="table-dark">
                    <tr>
                        <th class="text-center py-3">ID</th>
                        <th class="py-3">Nome Completo</th>
                        <th class="py-3">Idade</th>
                        <th class="py-3">Peso</th>
                        <th class="py-3">Altura</th>
                        <th class="text-center py-3">Ações Administrativas</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($lista as $pessoa): ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary"><?= $pessoa['idpessoa'] ?></td>
                            <td><?= $pessoa['nome'] . " " . $pessoa['sobrenome'] ?></td>
                            <td><?= $pessoa['idade'] ?> anos</td>
                            <td><?= $pessoa['peso'] ?> kg</td>
                            <td><?= $pessoa['altura'] ?> m</td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="formulario.php?id=<?= $pessoa['idpessoa'] ?>"
                                        class="btn btn-warning btn-sm fw-semibold">
                                        ✏️ Alterar
                                    </a>

                                    <a href="processarExclusoes.php?id=<?= $pessoa['idpessoa'] ?>"
                                        class="btn btn-danger btn-sm fw-semibold"
                                        onclick="return confirm('Tem certeza que deseja excluir da base de dados?')">
                                        🗑️ Excluir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    </div>
</div>

<?php include "html/rodape.php"; ?>