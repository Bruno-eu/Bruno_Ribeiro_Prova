<?php
session_start();
require 'conexao.php';

if($_SESSION['perfil'] !=1) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit();
}

if($_SERVER["REQUEST_METHOD"] =="POST") {
    $id_usuario = $_POST['id_usuario'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $id_perfil = $_POST['id_perfil'];
    $nova_senha = !empty($_POST['nova_senha'])? password_hash($_POST['nova_senha'],PASSWORD_DEFAULT): null;
    
    // Validação do nome - apenas letras e espaços
    if (!preg_match('/^[A-Za-zÀ-ÿ\s]+$/', $nome)) {
        echo "<script>alert('O nome deve conter apenas letras e espaços!'); window.location.href='alterar_usuario.php';</script>";
        exit();
    } elseif (strlen($nome) < 2) {
        echo "<script>alert('O nome deve ter pelo menos 2 caracteres!'); window.location.href='alterar_usuario.php';</script>";
        exit();
    }

    //atualiza os dados do usuario
    if($nova_senha){
        $sql = "UPDATE usuario SET nome=:nome,email=:email,id_perfil=:id_perfil,senha=:senha WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha',$nova_senha);
    } else {
        $sql="UPDATE usuario SET nome = :nome,email=:email,id_perfil=:id_perfil WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindParam(':nome',$nome);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':id_perfil',$id_perfil);
    $stmt->bindParam(':id',$id_usuario);

    if($stmt->execute()) {
        echo "<script>alert('Usuario atualizado com sucesso');window.location.href='buscar_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar usuario!');window.location.href='alterar_usuario.php?id=$id_usuario';</script>";
    }
}


?>