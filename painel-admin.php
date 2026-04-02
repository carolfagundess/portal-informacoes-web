<?php
// Lógica no topo [cite: 251]
include "funcoes-bd.php";
$conn = conectar();
$lista = listarRegistros($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - OMS</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-excluir { color: red; text-decoration: none; }
        .btn-editar { color: blue; text-decoration: none; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php">Início</a> | 
        <a href="formulario-coleta.php">Cadastrar Novo</a> | 
        <a href="painel-admin.php">Visualizar Registros</a>
    </nav>

    <h2>Painel Administrativo - Visualização de Dados [cite: 12]</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome Completo</th>
                <th>Idade</th>
                <th>Peso</th>
                <th>Altura</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pessoa = mysqli_fetch_array($lista)): ?> [cite: 57]
            <tr>
                <td><?= $pessoa['idpessoa'] ?></td>
                <td><?= $pessoa['nome'] . " " . $pessoa['sobrenome'] ?></td>
                <td><?= $pessoa['idade'] ?> anos</td>
                <td><?= $pessoa['peso'] ?> kg</td>
                <td><?= $pessoa['altura'] ?> m</td>
                <td>
                    <a href="form-alterar.php?id=<?= $pessoa['idpessoa'] ?>" class="btn-editar">Alterar</a> | 
                    <a href="processar-excluir.php?id=<?= $pessoa['idpessoa'] ?>" 
                       class="btn-excluir" 
                       onclick="return confirm('Tem certeza que deseja excluir este registro?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <footer>
        <hr>
        <p>Desenvolvedores: [Seu Nome] & [Nome da Dupla] - IFSul Venâncio Aires</p>
    </thead>

</body>
</html>
<?php desconectar($conn); ?>