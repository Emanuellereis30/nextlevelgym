<?php
header('Content-Type: application/json');
include "conexao.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');
$novaSenha = trim($data['novaSenha'] ?? '');

if (!$email || !$novaSenha) {
    echo json_encode(["error" => "E-mail e nova senha são obrigatórios."]);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "E-mail não encontrado."]);
    exit;
}

$user = $result->fetch_assoc();
$senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

$upd = $conn->prepare("UPDATE usuarios SET password=? WHERE id=?");
$upd->bind_param("si", $senhaHash, $user['id']);
$upd->execute();

echo json_encode(["message" => "Senha redefinida com sucesso!"]);
