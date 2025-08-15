<?php
session_start();
require 'conexao.php';

// Verifica se o usuario tem permissao de adm
if($_SESSION['perfil'] !=1) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit();

//indica variavel para armazenar usuarios
$usuarios = [];

//busca todos os usuarios cadastro em ordem alfabetica
$sql = "SELECT * FROM usuarios ORDER BY nome ASC"
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

//se um id for passado via get exclui o usuario
if(isset($_GET['id']) && is numeric($_get['id'])) {
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