<?php
session_start();
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass  = $_POST['senha'];

   
    $sql = "SELECT * FROM professores WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $professor = $result->fetch_assoc();

    if ($professor && password_verify($pass, $professor['senha'])) {
        $_SESSION['professor_id'] = $professor['id'];
        header("Location: professor.php"); 
        exit;
    } else {
        $erro = "Email ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login - NextLevelGym</title>
  <link rel="stylesheet" href="css/loginadm.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>
<body>
  <header class="header">
    <a href="index.php" class="logo">
      <img src="img/logopng.png" alt="Logo">
    </a>

    <div id="menu-btn" class="fas fa-bars"></div>

    <nav class="navbar">
      <a href="index.php">Início</a>
      <a href="sobre.php">Sobre</a>
      <a href="plano.php">Planos</a>
      <a href="trainers">Quero ser Aluno</a>
      <a href="login.php">Área do Usuário</a>
    </nav>
  </header>

  <div class="area-login">
    <h2>Área do Professor</h2>
    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>

    <form method="post" action="">
      <input type="email" name="email" placeholder="Seu e-mail" required
             autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false">

      <div class="area-senha">
        <input type="password" name="senha" id="senha" placeholder="Sua senha" required
               autocomplete="new-password" autocapitalize="none" autocorrect="off" spellcheck="false">
        <i class="fa-solid fa-eye toggle-senha" id="toggleSenha"></i>
      </div>

      <button type="submit" class="btn-primary">Entrar</button>
    </form>
  </div>

  <script>
  const senhaInput = document.getElementById('senha');
  const toggleSenha = document.getElementById('toggleSenha');

  toggleSenha.addEventListener('click', () => {
    const isPassword = senhaInput.type === 'password';
    senhaInput.type = isPassword ? 'text' : 'password';
    toggleSenha.classList.toggle('fa-eye');
    toggleSenha.classList.toggle('fa-eye-slash');
  });
  </script>
</body>
</html>
