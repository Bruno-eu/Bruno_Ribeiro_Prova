<?php
session_start();
require 'conexao.php';

// Verifica se o usuario tem permissao de adm
if($_SESSION['perfil'] !=1) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit();
}

//indica variavel para armazenar usuarios
$usuarios = [];

//busca todos os usuarios cadastro em ordem alfabetica
$sql = "SELECT * FROM usuario ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

//se um id for passado via get exclui o usuario
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];

    //exclui o usuario do banco de dados
    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id',$id_usuario,PDO::PARAM_INT);

    if($stmt->execute()) {
        echo "<script>alert('Usuario Excluido com sucesso!');window.location.href='excuir_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir usuario!');window.location.href='excuir_usuario.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excuir Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Excluir Usuario</h2>
    <?php if(!empty($usuarios)):?>    
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        <?php foreach($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['id_usuario'])?></td>
                <td><?= htmlspecialchars($usuario['nome'])?></td>
                <td><?= htmlspecialchars($usuario['email'])?></td>
                <td><?= htmlspecialchars($usuario['id_perfil'])?></td>
                <td>
                    <a href="excluir_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>" onclick="return confirm('Tem Certeza que deseja excluir este usuario')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p>Nenhum usuario encontrado</p>
        <?php endif; ?>

        <a href="principal.php">Voltar</a>
</body>
</html>