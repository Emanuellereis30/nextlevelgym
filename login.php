<?php
session_start();
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['senha'])) {
    $email = $_POST['email'];
    $pass  = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: aluno.php");
        exit;
    } else {
        $erro = "⚠️ E-mail ou senha incorretos. Verifique e tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - NextLevelGym</title>
<link rel="stylesheet" href="main.css">
<link rel="stylesheet" href="css/card.css">
<link rel="stylesheet" href="css/style.css">
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
<script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
</head>
<body>

<header class="header">
  <a href="index.php" class="logo"><img src="img/logopng.png" alt="Logo"></a>
  <div id="menu-btn" class="fas fa-bars"></div>
  <nav class="navbar">
    <a href="index.php">Início </a>
    <a href="sobre.php">Sobre</a>
    <a href="plano.php"> Planos</a>
    <a href="cadastro.php">Quero se Aluno</a>
    <a href="login.php">Área do Usuário</a>
  </nav>
</header>

<div class="area-login">
  <h2>LOGIN</h2>
  <?php if (!empty($erro)) echo "<p class='erro'>$erro</p>"; ?>
  <form method="post" action="login.php">
    <input type="email" name="email" placeholder="Seu e-mail" required autocomplete="off">

    <div class="area-senha">
      <input type="password" name="senha" id="senha" placeholder="Sua senha" required autocomplete="new-password">
      <i class="fa-solid fa-eye toggle-senha" id="toggleSenha"></i>
    </div>

    <button type="submit" class="btn-primary">Entrar</button>
  </form>

  <p><a href="#" id="esqueciSenha" class="btn-secundario">Esqueci minha senha</a></p>

  <div class="botoes-extra">
    <a href="cadastro.php" class="btn-secundario">Criar conta</a>
    <a href="loginadm.php" class="btn-secundario">Área do Administrador</a>
  </div>
</div>

<!-- Modal Esqueci a Senha -->
<div class="modal-fp" id="modalFP">
  <div class="modal-content">
    <h3>Recuperar senha</h3>
    <input type="email" id="fpEmail" placeholder="Seu e-mail" required>
    <div class="modal-buttons">
      <button class="btn-cancel" id="fpCancel">Cancelar</button>
      <button class="btn-send" id="fpSend">Enviar</button>
    </div>
    <div class="msg" id="fpMsg"></div>
  </div>
</div>

<script>
// Mostrar/Ocultar senha
const senhaInput = document.getElementById('senha');
const toggleSenha = document.getElementById('toggleSenha');

toggleSenha.addEventListener('click', () => {
  const isPassword = senhaInput.type === 'password';
  senhaInput.type = isPassword ? 'text' : 'password';
  toggleSenha.classList.toggle('fa-eye');
  toggleSenha.classList.toggle('fa-eye-slash');
});

// Modal Esqueci Senha
const esqueciBtn = document.getElementById('esqueciSenha');
const modalFP = document.getElementById('modalFP');
const cancelFP = document.getElementById('fpCancel');
const sendFP = document.getElementById('fpSend');
const fpMsg = document.getElementById('fpMsg');

esqueciBtn.addEventListener('click', e => {
  e.preventDefault();
  modalFP.style.display = 'flex';
  fpMsg.textContent = '';
});

cancelFP.addEventListener('click', () => {
  modalFP.style.display = 'none';
});

sendFP.addEventListener('click', async () => {
  const email = document.getElementById('fpEmail').value.trim();
  if (!email) { fpMsg.textContent = 'Informe seu e-mail.'; return; }
  fpMsg.textContent = 'Enviando...';

  try {
    const res = await fetch('redefinirsenha.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });
    const data = await res.json();
    fpMsg.textContent = data.message || data.error || 'Erro ao enviar.';
  } catch (err) {
    fpMsg.textContent = 'Erro de rede.';
  }
});
</script>

</body>
</html>
