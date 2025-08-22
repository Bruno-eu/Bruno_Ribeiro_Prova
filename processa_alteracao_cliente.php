<?php
session_start();
require 'conexao.php';

if($_SESSION['perfil'] !=1) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit();
}

if($_SERVER["REQUEST_METHOD"] =="POST") {
    $id_cliente = $_POST['id_cliente'];
    $nome_cliente = $_POST['nome_cliente'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $email = $_POST['email'];
    $nova_senha = !empty($_POST['nova_senha'])? password_hash($_POST['nova_senha'],PASSWORD_DEFAULT): null;

    //atualiza os dados do cliente
    if($nova_senha){
        $sql = "UPDATE cliente SET nome_cliente=:nome_cliente,email=:email,id_perfil=:id_perfil,senha=:senha WHERE id_cliente = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha',$nova_senha);
    } else {
        $sql="UPDATE cliente SET nome_cliente = :nome_cliente,telefone = :telefone,endereco = :endereco,email=:email WHERE id_cliente = :id";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindParam(':nome_cliente',$nome_cliente);
    $stmt->bindParam(':telefone',$telefone);
    $stmt->bindParam(':endereco',$endereco);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':id',$id_cliente);

    if($stmt->execute()) {
        echo "<script>alert('cliente atualizado com sucesso');window.location.href='buscar_cliente.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar cliente!');window.location.href='alterar_cliente.php?id=$id_cliente';</script>";
    }
}
?>