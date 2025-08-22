<?php
    session_start();
    require_once 'conexao.php';

    //Verifica se o cliente tem permissao de adm 
    if($_SESSION['perfil'] !=1) {
        echo"<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    }

    //inicializa variaveris
    $cliente = null;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(!empty($_POST['busca_cliente'])) {
            $busca = trim($_POST['busca_cliente']);

            //verifica se a busca é um numero (ID) ou um nome_cliente
            if(is_numeric($busca)) {
                $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':busca',$busca,PDO::PARAM_INT);
            } else {
                $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome_cliente";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':busca_nome_cliente',"$busca%",PDO::PARAM_STR);
            }

            $stmt->execute();
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            //se o cliente nao for encontrado, exibe um alerta
            if(!$cliente) {
                echo "<script>alert('cliente não encontrado!');</script>";
            }
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
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Alterar cliente</title>
        <link rel="stylesheet" href="styles.css">
        <!-- certifique-se de que o java script esta sendo carregado corretamente -->
        <script src="scripts.js"></script>
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

        <h2>Alterar cliente</h2>

        <form action="alterar_cliente.php" method="post">
            <label for="busca_cliente">Digite o ID ou Nome do cliente</label>
            <input type="text" id="busca_cliente" name="busca_cliente" required onkeyup="buscarSugestões()">
            
            <!-- div para exibir sugestoes de clientes -->
            <div id="sugestoes"></div>
            <button type="submit">Buscar</button>
        </form>
        
        <?php if($cliente):?>
            <!-- formulario para alterar cliente -->
            <form action="processa_alteracao_cliente.php" method="post">
                <input type="hidden" name="id_cliente" value="<?=htmlspecialchars($cliente['id_cliente'])?>">

                <label for="nome_cliente">Nome: </label>
                <input type="text" id="nome_cliente" name="nome_cliente" value="<?=htmlspecialchars($cliente['nome_cliente'])?>" required>

                <label for="telefone">Telefone: </label>
                <input type="text" id="telefone" name="telefone" value="<?=htmlspecialchars($cliente['telefone'])?>" required>

                <label for="endereco">Endereco: </label>
                <input type="text" id="endereco" name="endereco" value="<?=htmlspecialchars($cliente['endereco'])?>" required>

                <label for="email">Email: </label>
                <input type="email" id="email" name="email" value="<?=htmlspecialchars($cliente['email'])?>" required>

                <button type="submit">Alterar</button>
                <button type="reset">Cancelar</button>
            </form>
        <?php endif;?>
        <button type="button" class="btn-voltar" onclick="window.location.href='principal.php'">Voltar</button>

<center>
    <adress>
        Bruno Ribeiro | Estudante curso tecnico desenvolvimento de sistemas
    </adress>
</center>
</body>
</html>