<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e tem permissão
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] != 1) {
    http_response_code(403);
    exit('Acesso negado');
}

// Verifica se foi passado um termo de busca
if (!isset($_GET['busca']) || strlen($_GET['busca']) < 2) {
    echo json_encode([]);
    exit;
}

$busca = trim($_GET['busca']);

try {
    // Busca usuários que correspondam ao termo de busca
    $sql = "SELECT id_usuario, nome FROM usuario WHERE nome LIKE :busca ORDER BY nome ASC LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':busca', "$busca%", PDO::PARAM_STR);
    $stmt->execute();
    
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retorna os resultados em formato JSON
    header('Content-Type: application/json');
    echo json_encode($usuarios);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao buscar usuários']);
}
?>
