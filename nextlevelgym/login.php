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

    if (!$user) {
        $erro = "⚠️ Usuário não encontrado!";
    } else {
      // verificar se está bloqueado e se o bloqueio expirou (15 minutos para voltar)
      $bloqueado = $user['bloqueado'];
      $ultimoErro = $user['ultimo_erro'];
      $tempoAtual = new DateTime();
      $desbloqueio = false;

  if ($bloqueado && $ultimoErro) {
      $ultimoErroTime = new DateTime($ultimoErro);
      $interval = $tempoAtual->getTimestamp() - $ultimoErroTime->getTimestamp();

      if ($interval >= 900) { 
          $bloqueado = false;
          $desbloqueio = true;

          
          $stmt = $conn->prepare("UPDATE usuarios SET bloqueado=0, tentativas=0 WHERE email=?");
          $stmt->bind_param("s", $email);
          $stmt->execute();
      }
  }

  if ($bloqueado) {
      $erro = "⚠️ Sua conta está bloqueada. Tente novamente mais tarde ou entre em contato com o suporte.";
  } else {
      
      if (password_verify($pass, $user['password'])) {
          // login correto aqui reseta o numero de tentativas
          $stmt = $conn->prepare("UPDATE usuarios SET tentativas=0 WHERE email=?");
          $stmt->bind_param("s", $email);
          $stmt->execute();

          $_SESSION['user_id'] = $user['id'];
          header("Location: aluno.php");
          exit;
      } else {
          // senha incorreta aqui é para mudar o numero de tentativas
          $tentativas = $desbloqueio ? 1 : $user['tentativas'] + 1;

          if ($tentativas >= 3) {
              // bloquear usuário apos as tentativas 
              $stmt = $conn->prepare("UPDATE usuarios SET tentativas=?, bloqueado=1, ultimo_erro=NOW() WHERE email=?");
              $stmt->bind_param("is", $tentativas, $email);
              $stmt->execute();
              $erro = "⚠️ Sua conta foi bloqueada após 3 tentativas incorretas. Tente novamente em 15 minutos.";
          } else {
              // atualizar tentativas
              $stmt = $conn->prepare("UPDATE usuarios SET tentativas=?, ultimo_erro=NOW() WHERE email=?");
              $stmt->bind_param("is", $tentativas, $email);
              $stmt->execute();
              $erro = "⚠️ E-mail ou senha incorretos. Tentativa $tentativas de 3.";
          }
      }
  }
}
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - NextLevelGym</title>
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
    <a href="index.php">Início</a>
    <a href="sobre.php">Sobre</a>
    <a href="plano.php">Planos</a>
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

    <div class="forgot-wrapper">
        <a href="#" id="esqueciSenha" class="esqueci-senha">Esqueci minha senha</a>
    </div>


    <button type="submit" class="btn-primary">Entrar</button>
</form>

<div class="botoes-extra">
    <a href="cadastro.php" class="btn-primary">Criar conta</a>

    <div class="advanced-access">
        <button type="button" id="access-btn" class="btn-secundario">
            Acessos Avançados ▾
        </button>

        <div id="access-menu" class="hidden access-dropdown">
            <a href="loginprofessor.php">Área do Professor</a>
            <a href="loginadm.php">Área do Administrador</a>
        </div>
    </div>
</div>



<!-- Modal Esqueci a Senha -->
<div class="modal-fp" id="modalFP">
  <div class="modal-content">
    <div id="fpMsg"></div>
    <button class="btn-cancel" id="fpCancel">Fechar</button>
  </div>
</div>

<script>
// Mostrar/Ocultar senha
const senhaInput = document.getElementById('senha');
const toggleSenha = document.getElementById('toggleSenha');
toggleSenha.addEventListener('click', () => {
  senhaInput.type = senhaInput.type === 'password' ? 'text' : 'password';
  toggleSenha.classList.toggle('fa-eye');
  toggleSenha.classList.toggle('fa-eye-slash');
});

// Modal Esqueci Senha
const esqueciBtn = document.getElementById('esqueciSenha');
const modalFP = document.getElementById('modalFP');
const cancelFP = document.getElementById('fpCancel');
const fpMsg = document.getElementById('fpMsg');

esqueciBtn.addEventListener('click', e => {
  e.preventDefault();
  modalFP.style.display = 'flex';
  fpMsg.innerHTML = `
    <p>Digite seu e-mail e a nova senha:</p>
    <input type="email" id="fpEmail" placeholder="Seu e-mail">
    <input type="password" id="fpNovaSenha" placeholder="Nova senha">
    <button id="fpSendNova">Redefinir Senha</button>
  `;
});

cancelFP.addEventListener('click', () => modalFP.style.display = 'none');

// Redefinir senha
modalFP.addEventListener('click', e => {
  if (e.target.id !== 'fpSendNova') return;

  const email = document.getElementById('fpEmail').value.trim();
  const novaSenha = document.getElementById('fpNovaSenha').value.trim();

  if (!email || !novaSenha) {
    fpMsg.textContent = 'Preencha todos os campos.';
    return;
  }

  fpMsg.textContent = 'Redefinindo senha...';

  fetch('redefinirsenha.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, novaSenha })
  })
  .then(res => res.json())
  .then(data => {
    fpMsg.textContent = data.message || data.error || 'Erro ao redefinir senha.';
  })
  .catch(() => fpMsg.textContent = 'Erro de rede.');
});


document.getElementById("access-btn").addEventListener("click", function() {
    document.getElementById("access-menu").classList.toggle("hidden");
});

// Fechar o dropdown ao clicar fora
document.addEventListener('click', function(e) {
    const menu = document.getElementById("access-menu");
    const btn = document.getElementById("access-btn");

    if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add("hidden");
    }
});

</script>

</body>
</html>
