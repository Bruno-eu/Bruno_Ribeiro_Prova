<?php
session_start(); 
require_once 'conexao.php';

//Garante que o usuario esteja logado
if(!isset($_SESSION['id_usuario'])){
    echo "<script>alert('Acesso Negado!');window.location.href='index.php';</script>";
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if($nova_senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } elseif (strlen($nova_senha)<8) {
        echo "<script>alert('A senha deve ter pelo menos 8 caracteres!');</script>";
    } elseif ($nova_senha === "temp123") {
        echo "<script>alert('Escolha uma senha diferente da temporaria!');</script>";
    } else {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        //Atualiza a senha e remove o status de temporaria
        $sql="UPDATE usuario SET senha = :senha, senha_temporaria = 0 WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha',$senha_hash);
        $stmt->bindParam(':id',$id_usuario);

        if($stmt->execute()) {
            session_destroy(); //Finaliza a sessão
            echo "<script>alert('Senha alterada com sucesso! Faça login novamente');window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Erro ao alterar a senha!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="styles.css">
    <script src="validacoes.js"></script>
</head>
<body>
    <h2>Alterar Senha</h2>
    <p>Olá, <strong><?php echo $_SESSION['usuario'];?></strong>. Digite sua nova senha abaixo: </p>

    <form action="alterar_senha.php" method="post">
        <label for="nova_senha">Nova Senha</label>
        <input type="password" id="nova_senha" name="nova_senha" required minlength="8" oninput="validarSenha(this)">

        <label for="confirmar_senha"> Confirmar Nova Senha</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" required oninput="validarConfirmarSenha(document.getElementById('nova_senha'), this)">

        <label>
            <input type="checkbox" onclick="mostrarSenha()"> Mostrar Senha
        </label>
        
        <div id="requisitos-senha" style="text-align: left; margin: 10px 0; font-size: 12px; color: #666;">
            <strong>Requisitos da senha:</strong><br>
            • Mínimo de 8 caracteres<br>
            • As senhas devem coincidir
        </div>

        <button type="submit">Salvar nova senha</button>
    </form>
    
    <p><a href="index.php">Voltar para o Login</a></p>


</body>
</html>