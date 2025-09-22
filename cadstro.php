<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="main.css">
  <title>Cadastro - NextLevelGym</title>
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="header">
       <a href="index.php" class="logo">
          <img src="img/logopng.png" alt="Logo AutoFit">
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

<section class="about">
  <div class="content">
    <h3 class="title">Cadastre-se</h3>
    <form method="POST">
      <input type="text" name="name" placeholder="Nome completo" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Senha" required><br>
      <select name="plan" required>
        <option value="Básico">Plano Básico</option>
        <option value="Premium">Plano Premium</option>
      </select><br><br>
      <button type="submit" class="btn">Cadastrar</button>
    </form>
    <p>Já tem conta? <a href="login.php">Login</a></p>
  </div>
</section>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $plan  = $_POST['plan'];
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password, plan) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $pass, $plan);

    if ($stmt->execute()) {
        echo "<p>Cadastro realizado! <a href='login.php'>Clique aqui para login</a></p>";
    } else {
        echo "<p>Erro: " . $stmt->error . "</p>";
    }
}
?>
</body>
</html>
