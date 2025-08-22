<?php
session_start();
require_once 'conexao.php';

//Verifica se o cliente tem permissao de adm ou secretaria
if($_SESSION['perfil'] !=1 && $_SESSION['perfil'] !=2) {
    echo"<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

$cliente = []; //inicializa a variavel para evitar erros

//se formulario for enviado busca o cliente pelo id ou nome_cliente
if($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    //Verifica se a busca é um numero ou um nome_cliente
    if(is_numeric($busca)) {
        $sql="SELECT * FROM cliente WHERE id_cliente = :busca ORDER BY nome_cliente ASC";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':busca',$busca, PDO::PARAM_INT);
    } else {
            $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome_cliente ORDER BY nome_cliente ASC";
            $stmt=$pdo->prepare($sql);
            $stmt->bindValue(':busca_nome_cliente',"$busca%", PDO::PARAM_STR);
    }
} else {
        $sql="SELECT * FROM cliente ORDER BY nome_cliente ASC";
        $stmt=$pdo->prepare($sql);
}

$stmt->execute();
$clientes = $stmt->fetchALL(PDO::FETCH_ASSOC);

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
    <title>Buscar cliente</title>
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

    <h2>Lista de clientes</h2>

    <form action="buscar_cliente.php" method="POST">
        <label for="busca">Digite o ID ou nome(Opcional)</label>
        <input type="text" id="busca" name="busca">
        <button type="submit">Pesquisar</button>
    </form>
    <?php if(!empty($clientes)):?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Endereco</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>

            <?php foreach ($clientes as $cliente): ?>

            <tr>
                <td><?=htmlspecialchars($cliente['id_cliente'])?></td>
                <td><?=htmlspecialchars($cliente['nome_cliente'])?></td>
                <td><?=htmlspecialchars($cliente['telefone'])?></td>
                <td><?=htmlspecialchars($cliente['endereco'])?></td>
                <td><?=htmlspecialchars($cliente['email'])?></td>
                <td>
                    <a href="alterar_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente'])?>">Alterar</a>
                    <a href="excluir_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente'])?>" onclick="return confirm('Tem certeza que você quer excluir?')">Excluir</a>
                </td>
            </tr>

        <?php endforeach;?>
        </table>

    <?php else:?>
        <p>Nenhum cliente encontrado. </p>
    <?php endif;?>
    <button type="button" class="btn-voltar" onclick="window.location.href='principal.php'">Voltar</button>

<center>
    <adress>
        Bruno Ribeiro | Estudante curso tecnico desenvolvimento de sistemas
    </adress>
</center>
</body>
</html>