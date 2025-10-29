<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');
include "conexao.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');

if (empty($email)) {
    echo json_encode(["error" => "E-mail é obrigatório."]);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "E-mail não encontrado."]);
    exit;
}

$user = $result->fetch_assoc();
$token = bin2hex(random_bytes(32));
$tokenHash = hash('sha256', $token);
$expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

$stmt = $conn->prepare("UPDATE usuarios SET reset_token_hash=?, reset_expires_at=? WHERE id=?");
$stmt->bind_param("ssi", $tokenHash, $expires, $user['id']);
$stmt->execute();

// Aqui, simulamos envio de e-mail (pode ser substituído por PHPMailer)
$link = "http://localhost/seu_sistema/nova_senha.php?token=$token";
$mensagem = "Para redefinir sua senha, acesse o link: $link";

echo json_encode(["message" => "Simulação: link de recuperação enviado para $email"]);
exit;

?>
