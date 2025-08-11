<?php
session_start();
require_once 'conexao.php';

//Verifica se o usuario tem permição supondo que o perfil 1 seja o adm

if ($_SESSION['perfil']!=1) {
    echo "Acesso negado!"
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash ($_POST['senha'],PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];

    $sql="INSERT INTO usuario(nome,email,senha,id_perfil) VALUES (:nome,:email,:senha,:id_perfil)";
    $stmt $pdo->prepare($sql);

    $stmt->bindParam(':nome',$nome);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':senha',$senha);
    $stmt->bindParam(':id_perfil',$id_perfil);

    if($stmt->execute()) {
        echo "<script>alert('Usuario cadastrado com sucesso');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar usuario');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Principal</title>
    <link rel="slylesheet" href="styles.css">
</head>
<body>
    <h2>Cadastrar Usuario</h2>
    <form action="cadastro_usuario.php" method="POST">

    </form>
    
</body>
</html>