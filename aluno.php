<?php
session_start();
include "conexao.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Buscar dados do usuário
$sql = "SELECT * FROM usuarios WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$msg_checkin = "";
$msg_agendamento = "";

// === AÇÕES DO FORM ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check-in
    if (isset($_POST['checkin'])) {
        $stmtCheck = $conn->prepare("INSERT INTO checkins (usuarios_id) VALUES (?)");
        $stmtCheck->bind_param("i", $user_id);
        $msg_checkin = $stmtCheck->execute() ? "✅ Check-in realizado com sucesso!" : "❌ Erro ao realizar check-in.";
        $stmtCheck->close();
    }

    // Atualizar meta
    if (isset($_POST['nova_meta'])) {
        $nova_meta = trim($_POST['nova_meta']);
        $stmtMeta = $conn->prepare("UPDATE usuarios SET meta_treino=? WHERE id=?");
        $stmtMeta->bind_param("si", $nova_meta, $user_id);
        $stmtMeta->execute();
        $stmtMeta->close();
        $user['meta_treino'] = $nova_meta;
        $msg_checkin = "Meta atualizada!";
    }

    // Novo agendamento
    if (isset($_POST['agendar'])) {
        $modalidade = trim($_POST['modalidade']);
        $data_aula = $_POST['data_aula'];
        $horario = $_POST['horario'];

        $stmtAula = $conn->prepare("INSERT INTO agendamentos (aluno_id, modalidade, data_aula, horario) VALUES (?,?,?,?)");
        $stmtAula->bind_param("isss", $user_id, $modalidade, $data_aula, $horario);
        $msg_agendamento = $stmtAula->execute() ? "✅ Aula agendada com sucesso!" : "❌ Erro ao agendar aula.";
        $stmtAula->close();
    }

    // Editar agendamento
    if (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $modalidade = $_POST['modalidade'];
        $data_aula = $_POST['data_aula'];
        $horario = $_POST['horario'];

        $stmt = $conn->prepare("UPDATE agendamentos SET modalidade=?, data_aula=?, horario=? WHERE id=? AND aluno_id=?");
        $stmt->bind_param("sssii", $modalidade, $data_aula, $horario, $id, $user_id);
        $msg_agendamento = $stmt->execute() ? "✏️ Agendamento atualizado!" : "❌ Erro ao atualizar.";
        $stmt->close();
    }

    // Excluir agendamento
    if (isset($_POST['excluir'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM agendamentos WHERE id=? AND aluno_id=?");
        $stmt->bind_param("ii", $id, $user_id);
        $msg_agendamento = $stmt->execute() ? "🗑️ Agendamento excluído." : "❌ Erro ao excluir.";
        $stmt->close();
    }
}

// Check-ins
$checkins = $conn->query("SELECT * FROM checkins WHERE usuarios_id=$user_id ORDER BY checkin_time DESC LIMIT 5");

// Agendamentos
$agendamentos = $conn->query("SELECT * FROM agendamentos WHERE aluno_id=$user_id ORDER BY data_aula ASC, horario ASC");

// Próximas aulas fixas
$proximas_aulas = [
    "Segunda - 18:00 - Musculação",
    "Quarta - 19:00 - Pilates",
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel do Aluno</title>
<link rel="stylesheet" href="css/aluno.css">
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">

</head>
<body>

<header class="header">
  <a href="index.php" class="logo">
    <img src="img/logo.png" alt="Logo Next Level Gym" class="logo-img">
    <span><strong>Next</strong> Level Gym</span>
  </a>
</header>

<section class="area-aluno">
  <div class="card-unico">
    <h2>Bem-vindo, <?php echo $user['name']; ?>!</h2>

    <div class="secao">
      <h3>Informações</h3>
      <p><strong>E-mail:</strong> <?php echo $user['email']; ?></p>
      <p><strong>Plano:</strong> <?php echo $user['plan']; ?></p>
      <p><strong>Status:</strong> Ativo</p>
    </div>

    <div class="secao">
      <h3>Check-in</h3>
      <form method="post">
        <button type="submit" name="checkin" class="btn-primary">Fazer Check-in</button>
      </form>
      <?php if($msg_checkin): ?>
        <p class="checkin-msg"><?php echo $msg_checkin; ?></p>
      <?php endif; ?>
    </div>

    <div class="secao">
      <h3>Últimos Check-ins</h3>
      <?php if($checkins->num_rows > 0): ?>
        <ul>
          <?php while ($row = $checkins->fetch_assoc()): ?>
            <li><?php echo date("d/m/Y H:i", strtotime($row['checkin_time'])); ?></li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p>Nenhum check-in registrado.</p>
      <?php endif; ?>
    </div>

   
    <div class="secao agendamentos">
      <h3>Agende sua Aula Coletiva</h3>

      <?php if($msg_agendamento): ?>
        <p class="checkin-msg"><?php echo $msg_agendamento; ?></p>
      <?php endif; ?>

      <form method="post" class="form-agendar">
        <div class="form-group">
          <input type="text" name="modalidade" placeholder="Modalidade (Ex: Crossfit, Pilates...)" required>
          <input type="date" name="data_aula" required>
          <input type="time" name="horario" required>
        </div>
        <button type="submit" name="agendar" class="btn-primary">Agendar Aula</button>
      </form>

      <h4> Seus Agendamentos</h4>
      <?php if($agendamentos->num_rows > 0): ?>
        <div class="lista-agendamentos">
          <?php while($a = $agendamentos->fetch_assoc()): ?>
            <div class="agendamento-item">
              <div class="agendamento-info">
                <p><strong><?php echo htmlspecialchars($a['modalidade']); ?></strong></p>
                <p><?php echo date("d/m/Y", strtotime($a['data_aula'])); ?> às <?php echo date("H:i", strtotime($a['horario'])); ?></p>
              </div>
              <form method="post" class="form-editar">
                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                <input type="text" name="modalidade" value="<?php echo $a['modalidade']; ?>" required>
                <input type="date" name="data_aula" value="<?php echo $a['data_aula']; ?>" required>
                <input type="time" name="horario" value="<?php echo $a['horario']; ?>" required>
                <div class="botoes-editar">
                  <button type="submit" name="editar" class="btn-acao editar">Editar</button>
                  <button type="submit" name="excluir" class="btn-acao excluir">Excluir</button>
                </div>
              </form>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p class="sem-agendamento">Nenhum agendamento encontrado.</p>
      <?php endif; ?>
    </div>
  

    <div class="secao">
      <h3>Motivação do Dia</h3>
      <p>"O único treino ruim é aquele que não aconteceu. Continue se esforçando!"</p>
    </div>

    <div class="secao">
      <a href="logout.php" class="btn-primary">Sair</a>
    </div>

  </div>
</section>

</body>
</html>
