<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="main.css">
  <title>Painel do Aluno</title>
</head>
<body>
<header class="header">
  <a href="index.html" class="logo"><span>AUTO</span>FIT</a>
</header>

<section class="about">
  <div class="content">
    <h3 class="title">Bem-vindo, <?php echo $user['name']; ?></h3>
    <p>Plano contratado: <b><?php echo $user['plan']; ?></b></p>
    <a href="checkin.php" class="btn">Fazer Check-in</a>
    <a href="logout.php" class="btn">Sair</a>

    <h3 class="title">Seus Check-ins</h3>
    <?php
    $checkins = $conn->query("SELECT * FROM checkins WHERE user_id=$user_id ORDER BY checkin_time DESC");
    while ($row = $checkins->fetch_assoc()) {
        echo "<p>" . $row['checkin_time'] . "</p>";
    }
    ?>
  </div>
</section>
</body>
</html>
