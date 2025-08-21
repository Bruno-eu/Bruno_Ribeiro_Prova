<?php
session_start();
require_once 'conexao.php';

//Verifica se o usuario tem permissão supondo que o perfil 1 seja o admin
if (!isset($_SESSION['cliente']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $nome_cliente = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $email = $_POST['email'];
    $senha = password_hash ($_POST['senha'],PASSWORD_DEFAULT);
    $id_cliente = $_POST['id_cliente'];

    $sql="INSERT INTO cliente(nome_cliente,telefone,endereco,email,senha,id_cliente) VALUES (:nome_cliente,:telefone,:endereco,:email,:senha,:id_cliente)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_cliente',$nome_cliente);
    $stmt->bindParam(':telefone',$telefone);
    $stmt->bindParam(':endereco',$endereco);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':senha',$senha);
    $stmt->bindParam(':id_cliente',$id_cliente);

    if($stmt->execute()) {
        echo "<script>alert('cliente cadastrado com sucesso');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar cliente');</script>";
    }
}

$id_perfil = $_SESSION['perfil'];

$permissoes = [
    1 => ["Cadastrar"=>["cadastro_usuario.php","cadastro_perfil.php","cadastro_cliente.php","cadastro_fornecedor.php","cadastro_produto.php","cadastro_funcionario.php"],
          "Buscar"=>["buscar_usuario.php","buscar_perfil.php","buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php","buscar_funcionario.php"],
          "Alterar"=>["alterar_usuario.php","alterar_perfil.php","alterar_cliente.php","alterar_fornecedor.php","alterar_produto.php","alterar_funcionario.php"],
          "Excluir"=>["excluir_usuario.php","excluir_perfil.php","excluir_cliente.php","excluir_fornecedor.php","excluir_produto.php","excluir_funcionario.php"]],
    2 => ["Cadastrar"=>["cadastro_cliente.php"],
          "Buscar"=>["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
          "Alterar"=>["alterar_fornecedor.php","alterar_produto.php"],
          "Excluir"=>["excluir_produto.php"]],
    3 => ["Cadastrar"=>["cadastro_fornecedor.php","cadastro_produto.php"],
          "Buscar"=>["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
          "Alterar"=>["alterar_fornecedor.php","alterar_produto.php"],
          "Excluir"=>["excluir_produto.php"]],
    4 => ["Cadastrar"=>["cadastro_cliente.php"],
          "Buscar"=>["buscar_produto.php"],
          "Alterar"=>["alterar_cliente.php"]]
];

$opcoes_menu = $permissoes[$id_perfil];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Principal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<ul class="menu">
<?php foreach($opcoes_menu as $categoria =>$arquivos): ?>
    <li class="dropdown">
        <a href="#"><?=$categoria ?></a>
        <ul class="dropdown-menu">
        <?php foreach($arquivos as $arquivo): ?>
            <li>
                <a href="<?=$arquivo ?>"><?=ucfirst(str_replace("_"," ",basename($arquivo,".php")))?></a>
            </li>
        <?php endforeach; ?>
        </ul>
    </li>
<?php endforeach; ?>
</ul>

    <h2>Cadastrar cliente</h2>
    <form action="cadastro_cliente.php" method="POST">

        <label for="nome">Nome: </label>
        <input type="text" id="nome" name="nome" required>

        <label for="telefone">Telefone: </label>
        <input type="text" id="telefone" name="telefone" required>

        <label for="endereço">Endereço: </label>
        <input type="text" id="endereço" name="endereço" required>

        <label for="email">Email: </label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha: </label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>
    
    <button type="button" class="btn-voltar" onclick="window.location.href='principal.php'">Voltar</button>

    <center>
            <adress>
                Bruno Henrique Ribeiro | Estudante curso tecnico desenvolvimento de sistemas
                Eu não consegui fazer, não vou ficar dando desculpa só não consigo pensar nisso
            </adress>
    </center>
</body>
</html>