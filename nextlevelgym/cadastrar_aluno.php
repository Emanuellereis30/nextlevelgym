<?php
require_once("conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome  = $_POST["nome"] ?? '';
    $cpf   = $_POST["cpf"] ?? '';
    $email = $_POST["email"] ?? '';
    $senha = $_POST["senha"] ?? '';
    $plano = $_POST["plano"] ?? '';

    if(empty($nome) || empty($cpf) || empty($email) || empty($senha) || empty($plano)){
        echo "<script>alert('Todos os campos são obrigatórios!'); window.location.href='admin.php';</script>";
        exit;
    }

    // Verifica se o email já existe
    $stmtCheck = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if($resultCheck->num_rows > 0){
        echo "<script>alert('Este e-mail já está cadastrado!'); window.location.href='admin.php';</script>";
        exit;
    }

    // Verifica se o CPF já existe
    $stmtCPF = $conn->prepare("SELECT id FROM usuarios WHERE cpf = ?");
    $stmtCPF->bind_param("s", $cpf);
    $stmtCPF->execute();
    $resultCPF = $stmtCPF->get_result();

    if($resultCPF->num_rows > 0){
        echo "<script>alert('Este CPF já está cadastrado!'); window.location.href='admin.php';</script>";
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // INSERE O ALUNO
    $stmt = $conn->prepare("INSERT INTO usuarios (name, cpf, email, password, plan) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $cpf, $email, $senha_hash, $plano);

    if($stmt->execute()){
        echo "<script>alert('Aluno cadastrado com sucesso!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar aluno!'); window.location.href='admin.php';</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
