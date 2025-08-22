<?php
session_start();
require_once 'conexao.php';

// Verifica permissão (perfil 1 = admin)
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura e normaliza espaços (troca múltiplos espaços por um só)
    $nome = trim($_POST['nome']);
    $nome = preg_replace('/\s+/u', ' ', $nome);

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $id_perfil = $_POST['id_perfil'];

    // Validação do nome: somente letras (com acentos) e espaço entre palavras
    // Ex.: "Maria Clara", "João da Silva"
    if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ]+(?: [A-Za-zÀ-ÖØ-öø-ÿ]+)*$/u', $nome)) {
        echo "<script>alert('O nome deve conter apenas letras e espaços (sem números/símbolos).');</script>";
    } elseif (mb_strlen($nome, 'UTF-8') < 2) {
        echo "<script>alert('O nome deve ter pelo menos 2 caracteres.');</script>";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (nome, email, senha, id_perfil)
                VALUES (:nome, :email, :senha, :id_perfil)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        // IMPORTANTE: salva o hash, não a senha em texto puro
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id_perfil', $id_perfil);

        if ($stmt->execute()) {
            echo "<script>alert('Usuário cadastrado com sucesso');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar usuário');</script>";
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
    <title>Painel Principal</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Se validacoes.js tinha uma função que removia espaços, esta função abaixo sobrescreve -->
    <script>
      // Permite letras (com acento) e espaço. Remove números/símbolos.
      function validarNome(el) {
        el.value = el.value
          .replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]/g, '') // mantém letras + espaço
          .replace(/\s+/g, ' ');               // normaliza múltiplos espaços
      }
      function validarSenha(el) {
        // exemplo simples: apenas comprimento mínimo (você pode reforçar depois)
        if (el.value.length < 8) return;
      }
    </script>
</head>
<body>
<ul class="menu">
<?php foreach($opcoes_menu as $categoria => $arquivos): ?>
    <li class="dropdown">
        <a href="#"><?= $categoria ?></a>
        <ul class="dropdown-menu">
        <?php foreach($arquivos as $arquivo): ?>
            <li>
                <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_"," ",basename($arquivo,".php"))) ?></a>
            </li>
        <?php endforeach; ?>
        </ul>
    </li>
<?php endforeach; ?>
</ul>

    <h2>Cadastrar Usuário</h2>
    <form action="cadastro_usuario.php" method="POST">

        <label for="nome">Nome: </label>
        <!-- pattern permite uma ou mais palavras separadas por espaço -->
        <input type="text" id="nome" name="nome" required
               pattern="^[A-Za-zÀ-ÖØ-öø-ÿ]+(?: [A-Za-zÀ-ÖØ-öø-ÿ]+)*$"
               title="Digite apenas letras e espaço entre palavras"
               oninput="validarNome(this)">

        <label for="email">Email: </label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha: </label>
        <input type="password" id="senha" name="senha" required minlength="8" oninput="validarSenha(this)">

        <div id="requisitos-senha" style="text-align: left; margin: 10px 0; font-size: 12px; color: #666;">
            <strong>Requisitos da senha:</strong><br>
            • Mínimo de 8 caracteres
        </div>

        <label for="id_perfil">Perfil</label>
        <select id="id_perfil" name="id_perfil">
            <option value="1">Administrador</option>
            <option value="2">Secretaria</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>
        </select>

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>

    <button type="button" class="btn-voltar" onclick="window.location.href='principal.php'">Voltar</button>
</body>
</html>
