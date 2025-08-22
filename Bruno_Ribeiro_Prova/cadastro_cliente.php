<?php
session_start();
require_once 'conexao.php';

// Debug: verificar se a sessão está funcionando
if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Usuário não está logado!'); window.location.href='index.php';</script>";
    exit();
}

if (!isset($_SESSION['perfil'])) {
    echo "<script>alert('Perfil não definido!'); window.location.href='index.php';</script>";
    exit();
}

//Verifica se o usuario tem permissão supondo que o perfil 1 seja o adm
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado! Apenas administradores podem cadastrar clientes.'); window.location.href='principal.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $nome_cliente = trim($_POST['nome_cliente']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $id_funcionario_responsavel = $_SESSION['id_usuario'] ?? null;
    
    // Validação do nome - apenas letras e espaços
    if (!preg_match('/^[A-Za-zÀ-ÿ\s]+$/', $nome_cliente)) {
        echo "<script>alert('O nome deve conter apenas letras e espaços!');</script>";
    } elseif (strlen($nome_cliente) < 2) {
        echo "<script>alert('O nome deve ter pelo menos 2 caracteres!');</script>";
    } elseif (empty($endereco)) {
        echo "<script>alert('O endereço é obrigatório!');</script>";
    } elseif (empty($telefone)) {
        echo "<script>alert('O telefone é obrigatório!');</script>";
    } elseif (strlen(preg_replace('/\D/', '', $telefone)) < 10) {
        echo "<script>alert('O telefone deve ter pelo menos 10 dígitos!');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email inválido: " . htmlspecialchars($email) . "');</script>";
    } else {
        // Verificar se o email já existe
        $sql_check = "SELECT id_cliente FROM cliente WHERE email = :email";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() > 0) {
            echo "<script>alert('Este email já está cadastrado!');</script>";
        } else {
            $sql="INSERT INTO cliente(nome_cliente, endereco, telefone, email, id_funcionario_responsavel) VALUES (:nome_cliente, :endereco, :telefone, :email, :id_funcionario_responsavel)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':nome_cliente', $nome_cliente);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id_funcionario_responsavel', $id_funcionario_responsavel);

            if($stmt->execute()) {
                echo "<script>alert('Cliente cadastrado com sucesso!'); window.location.href='principal.php';</script>";
            } else {
                echo "<script>alert('Erro ao cadastrar cliente');</script>";
            }
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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <script src="validacoes.js"></script>
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

    <div class="container">
        <h2>Cadastrar Cliente</h2>
        <form action="cadastro_cliente.php" method="POST" class="form-cadastro">

            <label for="nome_cliente">Nome do Cliente: </label>
            <input type="text" id="nome_cliente" name="nome_cliente" required pattern="[A-Za-zÀ-ÿ\s]+" title="Digite apenas letras e espaços" oninput="validarNome(this)">
            
            <label for="endereco">Endereço: </label>
            <input type="text" id="endereco" name="endereco" required>
            
            <label for="telefone">Telefone: </label>
            <input type="tel" id="telefone" name="telefone" required placeholder="(00) 0000-0000" oninput="validarTelefone(this)">
            
            <label for="email">Email: </label>
            <input type="email" id="email" name="email" required>

            <div class="botoes">
                <button type="submit">Salvar</button>
                <button type="reset">Cancelar</button>
            </div>
        </form>
        
        <button type="button" class="btn-voltar" onclick="window.location.href='principal.php'">Voltar</button>
    </div>

</body>
</html>