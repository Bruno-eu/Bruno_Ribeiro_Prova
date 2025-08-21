<?php
session_start();
require 'conexao.php';

// Verifica se o usuario tem permissao de adm
if($_SESSION['perfil'] !=1) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit();
}

//indica variavel para armazenar usuarios
$clientes = [];

//busca todos os usuarios cadastro em ordem alfabetica
$sql = "SELECT * FROM cliente ORDER BY nome_cliente ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

//se um id for passado via get exclui o usuario
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_cliente = $_GET['id'];

    //exclui o usuario do banco de dados
    $sql = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id',$id_cliente,PDO::PARAM_INT);

    if($stmt->execute()) {
        echo "<script>alert('Cliente Excluido com sucesso!');window.location.href='excuir_cliente.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir cliente!');window.location.href='excuir_cliente.php';</script>";
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
    <title>Excuir Cliente</title>
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
    <h2>Excluir Cliente</h2>
    <?php if(!empty($clientes)):?>    
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Endreco</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        <?php foreach($clientes as $cliente): ?>
            <tr>
                <td><?= htmlspecialchars($cliente['id_cliente'])?></td>
                <td><?= htmlspecialchars($cliente['nome_cliente'])?></td>
                <td><?= htmlspecialchars($cliente['telefone'])?></td>
                <td><?= htmlspecialchars($cliente['endereco'])?></td>
                <td><?= htmlspecialchars($cliente['email'])?></td>
                <td><?= htmlspecialchars($cliente['id_perfil'])?></td>
                <td>
                    <a href="excluir_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente'])?>" onclick="return confirm('Tem Certeza que deseja excluir este cliente')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p>Nenhum cliente encontrado</p>
        <?php endif; ?>

        <button type="button" class="btn-voltar" onclick="window.location.href='principal.php'">Voltar</button>
</body>
</html>