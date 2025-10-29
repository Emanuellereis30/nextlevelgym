<?php
require_once("conexao.php");

// Apenas JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = $_POST["nome"] ?? '';
    $email = $_POST["email"] ?? '';
    $senha = $_POST["senha"] ?? '';
    $plano = $_POST["plano"] ?? '';

    if(empty($nome) || empty($email) || empty($senha) || empty($plano)){
        echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    // Verifica email
    $stmtCheck = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if($resultCheck->num_rows > 0){
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (name, email, password, plan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $plano);

    if($stmt->execute()){
        echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar aluno.']);
    }

    $stmt->close();
    $conn->close();
    exit; // importante: encerra o script aqui
}
?>
