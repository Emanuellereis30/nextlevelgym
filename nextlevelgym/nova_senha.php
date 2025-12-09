<?php
include "conexao.php";

$token = $_GET['token'] ?? '';
$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $novaSenha = trim($_POST["senha"]);
    $confirmarSenha = trim($_POST["confirmar_senha"]);

    if (empty($novaSenha) || empty($confirmarSenha)) {
        $erro = "Preencha todos os campos.";
    } elseif ($novaSenha !== $confirmarSenha) {
        $erro = "As senhas não coincidem.";
    } else {
        $tokenHash = hash('sha256', $token);
        $sql = "SELECT * FROM usuarios WHERE reset_token_hash = ? AND reset_expires_at > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $tokenHash);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $erro = "Token inválido ou expirado.";
        } else {
            $user = $result->fetch_assoc();
            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE usuarios SET password=?, reset_token_hash=NULL, reset_expires_at=NULL WHERE id=?");
            $update->bind_param("si", $novaSenhaHash, $user['id']);
            $update->execute();

            $sucesso = " Senha redefinida com sucesso! Você já pode fazer login.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Redefinir Senha - NextLevelGym</title>
<link rel="stylesheet" href="css/novasenha.css">
<link rel="stylesheet" href="css/style.css">
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>
<body>

<div class="area-login">
  <h2>Redefinir Senha</h2>

  <?php if (!empty($erro)) echo "<p class='erro'>$erro</p>"; ?>
  <?php if (!empty($sucesso)) echo "<p class='sucesso'>$sucesso</p>"; ?>

  <?php if (empty($sucesso)) : ?>
  <form method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="password" name="senha" placeholder="Nova senha" required>
    <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required>
    <button type="submit">Salvar nova senha</button>
  </form>
  <?php endif; ?>

  <a href="login.php" class="voltar">Voltar para o login</a>
</div>

</body>
</html>
