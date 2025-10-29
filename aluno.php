<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM usuarios WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$msg_checkin = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkin'])) {
    $stmtCheck = $conn->prepare("INSERT INTO checkins (usuarios_id) VALUES (?)");
    $stmtCheck->bind_param("i", $user_id);
    if ($stmtCheck->execute()) {
        $msg_checkin = "✅ Check-in realizado com sucesso!";
    } else {
        $msg_checkin = "❌ Erro ao realizar check-in.";
    }
    $stmtCheck->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/aluno.css">
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
  <title>Painel do Aluno</title>
</head>
<body>

<header class="header">
  <a href="index.php" class="logo">
    <img src="img/logo.png" alt="Logo Next Level Gym" class="logo-img">
    <span><strong>Next</strong> Level Gym</span>
  </a>
</header>


<section class="about">
  <div class="content">
    <h3 class="title">Bem-vindo, <?php echo $user['name']; ?></h3>
    <p>Plano contratado: <b><?php echo $user['plan']; ?></b></p>

    
    <form method="post">
        <button type="submit" name="checkin" class="btn">Fazer Check-in</button>
    </form>

    <?php if($msg_checkin): ?>
        <p class="checkin-msg"><?php echo $msg_checkin; ?></p>
    <?php endif; ?>

    <a href="logout.php" class="btn">Sair</a>

    <h3 class="title">Seus Check-ins</h3>
    <?php
    $checkins = $conn->query("SELECT * FROM checkins WHERE usuarios_id=$user_id ORDER BY checkin_time DESC");
    while ($row = $checkins->fetch_assoc()) {
        echo "<p class='checkin'>" . $row['checkin_time'] . "</p>";
    }
    ?>
  </div>
</section>
</body>
</html>
