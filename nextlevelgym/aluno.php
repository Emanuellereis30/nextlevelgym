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
$msg_agendamento = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* check-in */
    if (isset($_POST['checkin'])) {
        $stmtCheck = $conn->prepare("INSERT INTO checkins (usuarios_id) VALUES (?)");
        $stmtCheck->bind_param("i", $user_id);
        $msg_checkin = $stmtCheck->execute() ? "✅ Check-in realizado!" : "❌ Erro ao fazer check-in.";
        $stmtCheck->close();
    }

    /* agendamento com limite */
    if (isset($_POST['agendar'])) {
        $modalidade = trim($_POST['modalidade']);
        $professor_id = $_POST['professor_id'];
        $data_aula = $_POST['data_aula'];
        $horario = $_POST['horario'];

        // Limite máximo por horário
        $LIMITE_AULA = 20;

        // Consulta quantos já estão agendados
        $count = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM agendamentos
            WHERE modalidade=? AND professor_id=? AND data_aula=? AND horario=?
        ");
        $count->bind_param("siss", $modalidade, $professor_id, $data_aula, $horario);
        $count->execute();
        $result = $count->get_result()->fetch_assoc();
        $total = $result['total'];

        if ($total >= $LIMITE_AULA) {
            $msg_agendamento = "❌ Limite de alunos atingido para esta aula!";
        } else {

           
            $stmtAula = $conn->prepare("
                INSERT INTO agendamentos (aluno_id, modalidade, professor_id, data_aula, horario)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmtAula->bind_param("isiss", $user_id, $modalidade, $professor_id, $data_aula, $horario);

            if ($stmtAula->execute()) {
                $msg_agendamento = "✅ Aula agendada!";
            } else {
                if ($conn->errno == 1062) {
                    $msg_agendamento = "❌ Outro aluno acabou de marcar este horário. Tente outro!";
                } else {
                    $msg_agendamento = "❌ Erro ao agendar.";
                }
            }

            $stmtAula->close();
        }

        $count->close();
    }

    /* editar agendamento  */
    if (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $modalidade = $_POST['modalidade'];
        $professor_id = $_POST['professor_id'];
        $data_aula = $_POST['data_aula'];
        $horario = $_POST['horario'];

        $stmt = $conn->prepare("
            UPDATE agendamentos 
            SET modalidade=?, professor_id=?, data_aula=?, horario=? 
            WHERE id=? AND aluno_id=?
        ");
        $stmt->bind_param("sissii", $modalidade, $professor_id, $data_aula, $horario, $id, $user_id);

        $msg_agendamento = $stmt->execute() ? "Agendamento atualizado!" : "❌ Erro ao atualizar.";
        $stmt->close();
    }

    /* excluir agendamento */
    if (isset($_POST['excluir'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM agendamentos WHERE id=? AND aluno_id=?");
        $stmt->bind_param("ii", $id, $user_id);
        $msg_agendamento = $stmt->execute() ? "Agendamento excluído." : "❌ Erro ao excluir.";
        $stmt->close();
    }
}

$checkins = $conn->query("
    SELECT * FROM checkins 
    WHERE usuarios_id=$user_id 
    ORDER BY checkin_time DESC LIMIT 5
");

$agendamentos = $conn->query("
    SELECT a.id, a.modalidade, a.data_aula, a.horario, p.nome AS professor
    FROM agendamentos a
    LEFT JOIN professores p ON a.professor_id = p.id
    WHERE a.aluno_id = $user_id
    ORDER BY a.data_aula ASC, a.horario ASC
");

$professores_select = $conn->query("SELECT id, nome FROM professores ORDER BY nome ASC");
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
    <h2>Bem-vindo, <?= $user['name'] ?>!</h2>

    <div class="secao">
      <h3>Informações</h3>
      <p><strong>E-mail:</strong> <?= $user['email'] ?></p>
      <p><strong>Plano:</strong> <?= $user['plan'] ?></p>
      <p><strong>Status:</strong> Ativo</p>
    </div>

    <div class="secao">
      <h3>Check-in</h3>
      <form method="post">
        <button type="submit" name="checkin" class="btn-primary">Fazer Check-in</button>
      </form>
      <?php if($msg_checkin): ?>
        <p class="checkin-msg"><?= $msg_checkin ?></p>
      <?php endif; ?>
    </div>

    <div class="secao">
      <h3>Últimos Check-ins</h3>
      <?php if($checkins->num_rows > 0): ?>
        <ul>
          <?php while ($row = $checkins->fetch_assoc()): ?>
            <li><?= date("d/m/Y H:i", strtotime($row['checkin_time'])) ?></li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p>Nenhum check-in registrado.</p>
      <?php endif; ?>
    </div>

    <div class="secao agendamentos">
      <h3>Agende sua Aula</h3>

      <?php if($msg_agendamento): ?>
        <p class="checkin-msg"><?= $msg_agendamento ?></p>
      <?php endif; ?>

    <form method="post" class="form-agendar">
      <div class="form-group">
        <select name="modalidade" required>
          <option value="">Modalidade</option>
          <option>Funcional</option>
          <option>Crossfit</option>
          <option>Pilates</option>
          <option>Consulta com Personal</option>
          <option>Consulta com Nutricionista</option>
        </select>

        <select name="professor_id" required>
          <option value="">Professor</option>
          <?php while($p = $professores_select->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
          <?php endwhile; ?>
        </select>

        <input type="date" name="data_aula" required>
        <input type="time" name="horario" required>
      </div>
      <button type="submit" name="agendar" class="btn-primary">Agendar Aula</button>
    </form>

    <h4>Seus Agendamentos</h4>
    <?php if($agendamentos->num_rows > 0): ?>
      <div class="lista-agendamentos">
        <?php while($a = $agendamentos->fetch_assoc()): ?>
          <div class="agendamento-item">
            <div class="agendamento-info">
              <p><strong><?= htmlspecialchars($a['modalidade']) ?></strong></p>
              <p><?= date("d/m/Y", strtotime($a['data_aula'])) ?> às <?= date("H:i", strtotime($a['horario'])) ?></p>
              <p><strong>Professor:</strong> <?= htmlspecialchars($a['professor'] ?? 'Não definido') ?></p>
            </div>
            <form method="post" class="form-editar">
              <input type="hidden" name="id" value="<?= $a['id'] ?>">
              <input type="text" name="modalidade" value="<?= $a['modalidade'] ?>" required>
              <select name="professor_id" required>
                <option value="">Selecione</option>
                <?php
                $professores_edit = $conn->query("SELECT id, nome FROM professores ORDER BY nome ASC");
                while($p = $professores_edit->fetch_assoc()):
                ?>
                  <option value="<?= $p['id'] ?>" <?= $p['id'] == $a['professor'] ? 'selected' : '' ?>><?= $p['nome'] ?></option>
                <?php endwhile; ?>
              </select>
              <input type="date" name="data_aula" value="<?= $a['data_aula'] ?>" required>
              <input type="time" name="horario" value="<?= $a['horario'] ?>" required>
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
