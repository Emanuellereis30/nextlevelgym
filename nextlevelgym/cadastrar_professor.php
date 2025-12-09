<?php
require_once("conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"] ?? '';
    $email = $_POST["email"] ?? '';
    $senha = $_POST["senha"] ?? '';
    $especialidade = $_POST["especialidade"] ?? '';

    if (empty($nome) || empty($email) || empty($senha) || empty($especialidade)) {
        echo "<script>alert('Preencha todos os campos!');history.back();</script>";
        exit;
    }

    $stmtCheck = $conn->prepare("SELECT id FROM professores WHERE email = ?");
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        echo "<script>alert('Este e-mail já está cadastrado!');history.back();</script>";
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO professores (nome, email, senha, especialidade) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $especialidade);

    if ($stmt->execute()) {
        echo "<script>alert('Professor cadastrado com sucesso!');window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar professor.');history.back();</script>";
    }
}
?>
