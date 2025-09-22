<?php
session_start();
include "conexao.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="main.css">
  <title>Login - NextLevelGym</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/login.css">
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>
<body>
      <header class="header">
       <a href="index.php" class="logo">
          <img src="img/logopng.png" alt="Logo">
       </a>


        <div id="menu-btn" class="fas fa-bars"></div>

        <nav class="navbar">
            <a href="index.php">Início </a>
            <a href="sobre.php">Sobre</a>
            <a href="servicos.php">Nossos Serviços</a>
            <a href="plano.php"> Planos</a>
            <a href="trainers">Quero se Aluno</a>
            <a href="login.php">Área do Usuário</a>
        </nav>

    </header>


<div class="login-container">
  <h2>Entrar na Next Level Gym</h2>
  <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
  <form method="post" action="login.php">
    <input type="email" name="email" placeholder="Seu e-mail" required>

    <div class="senha-container">
      <input type="password" name="senha" id="senha" placeholder="Sua senha" required>
      <i class="fa-solid fa-eye toggle-senha" id="toggleSenha"></i>
    </div>

    <button type="submit" class="btn-primary">Entrar</button>
  </form>

  <div class="botoes-extra">
    <a href="cadastro.php" class="btn-secundario">Criar conta</a>
    <a href="index.php" class="btn-secundario">Voltar</a>
  </div>
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

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email=?";
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
        echo "<p>Email ou senha inválidos!</p>";
    }
}
?>