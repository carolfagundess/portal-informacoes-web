<?php
include "funcoes-bd.php";

// Recebe o ID via GET conforme padrão de links [cite: 111]
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn = conectar();
    
    if (excluir($conn, $id)) {
        echo "<script>alert('Registro excluído!'); window.location.href='painel-admin.php';</script>";
    } else {
        echo "Erro ao excluir.";
    }
    
    desconectar($conn);
} else {
    header("Location: painel-admin.php");
}
?>