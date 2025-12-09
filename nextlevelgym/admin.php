<?php
require_once("conexao.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Editar aluno
    if (isset($_POST['editar_aluno'])) {
        $id = intval($_POST['id']);
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $cpf = trim($_POST['cpf']);
        $plan = trim($_POST['plano']);

        $stmt = $conn->prepare("UPDATE usuarios SET name = ?, email = ?, cpf = ?, plan = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nome, $email, $cpf, $plan, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Editar professor
    if (isset($_POST['editar_professor'])) {
        $id = intval($_POST['id']);
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $cpf = trim($_POST['cpf']);
        $especialidade = trim($_POST['especialidade']);

        $stmt = $conn->prepare("UPDATE professores SET nome = ?, email = ?, cpf = ?, especialidade = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nome, $email, $cpf, $especialidade, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Agendamento adicionar
    if (isset($_POST['adicionar'])) {
        $aluno_id = intval($_POST['aluno_id']);
        $professor_id = intval($_POST['professor_id']);
        $modalidade = $_POST['modalidade'];
        $data_aula = $_POST['data_aula'];
        $horario = $_POST['horario'];

        $LIMITE_AULA = 20;

        $check = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM agendamentos
            WHERE professor_id=? 
            AND modalidade=? 
            AND data_aula=? 
            AND horario=?
        ");
        $check->bind_param("isss", $professor_id, $modalidade, $data_aula, $horario);
        $check->execute();
        $result = $check->get_result()->fetch_assoc();
        $check->close();
        $total = $result['total'];

        if ($total >= $LIMITE_AULA) {
            echo "<script>alert('❌ Limite de alunos atingido para esta aula!');</script>";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO agendamentos (aluno_id, professor_id, modalidade, data_aula, horario, criado_em)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param("iisss", $aluno_id, $professor_id, $modalidade, $data_aula, $horario);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Agendamento editar
    if (isset($_POST['alterar'])) {
        $id = intval($_POST['id']);
        $modalidade = $_POST['modalidade'];
        $data_aula = $_POST['data_aula'];
        $horario = $_POST['horario'];

        $stmt = $conn->prepare("UPDATE agendamentos SET modalidade = ?, data_aula = ?, horario = ? WHERE id = ?");
        $stmt->bind_param("sssi", $modalidade, $data_aula, $horario, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Agendamento cancelar 
    if (isset($_POST['cancelar'])) {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM agendamentos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}


$sql_alunos = "SELECT * FROM usuarios ORDER BY id DESC";
$result_alunos = $conn->query($sql_alunos);

$sql_professores = "SELECT * FROM professores ORDER BY id DESC";
$result_professores = $conn->query($sql_professores);

$sql_checkins = "
    SELECT c.id AS checkin_id, u.name, u.email, u.plan, c.checkin_time
    FROM checkins c
    JOIN usuarios u ON c.usuarios_id = u.id
    ORDER BY c.checkin_time DESC
";
$result_checkins = $conn->query($sql_checkins);

$sql_agendamentos = "
SELECT a.id, 
       u.name AS aluno, 
       u.cpf AS aluno_cpf,
       p.nome AS professor,
       a.modalidade, 
       a.data_aula, 
       a.horario, 
       a.criado_em
FROM agendamentos a
JOIN usuarios u ON a.aluno_id = u.id
JOIN professores p ON a.professor_id = p.id
ORDER BY a.data_aula DESC";
$result_agendamentos = $conn->query($sql_agendamentos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Página do Administrador</title>
<link rel="stylesheet" href="css/admin.css">
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">

</head>
<body>

<div class="navbar">
    <a href="index.php"><img class="logo" src="img/logopng.png" alt="Logo"></a>
    <h1>Painel do Administrador</h1>
</div>

<div class="container">

    <!-- Alunos -->
    <h2>Alunos</h2>
    <input type="text" id="filtro-aluno" placeholder="Pesquisar por CPF..." onkeyup="filtrarAlunos()">
    <button class="add" onclick="openModalAluno()">+ Novo Aluno</button>

    <table>
        <tr>
            <th>ID</th><th>Nome</th><th>CPF</th><th>Email</th><th>Plano</th><th>Criado em</th><th>Ações</th>
        </tr>
        <?php while($aluno = $result_alunos->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($aluno['id']) ?></td>
            <td><?= htmlspecialchars($aluno['name']) ?></td>
            <td><?= htmlspecialchars($aluno['cpf']) ?></td>
            <td><?= htmlspecialchars($aluno['email']) ?></td>
            <td><?= htmlspecialchars($aluno['plan']) ?></td>
            <td><?= htmlspecialchars($aluno['created_at'] ?? $aluno['created_at']) ?></td>
            <td>
                
                <button class="edit" onclick="openEditModalAluno('<?= htmlspecialchars($aluno['id']) ?>','<?= addslashes(htmlspecialchars($aluno['name'])) ?>','<?= addslashes(htmlspecialchars($aluno['email'])) ?>','<?= addslashes(htmlspecialchars($aluno['cpf'])) ?>','<?= addslashes(htmlspecialchars($aluno['plan'])) ?>')">✏️ Editar</button>
                <a href="removeraluno.php?id=<?= $aluno['id'] ?>" onclick="return confirm('Deseja realmente excluir?')"><button class="delete">🗑️ Excluir</button></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Modal Editar Aluno -->
<div id="modalEditarAluno" class="modal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="tituloEditarAluno">
        <span class="close" onclick="fecharModal('modalEditarAluno')">&times;</span>
        <h2 id="tituloEditarAluno">Editar Aluno</h2>

        <form id="formEditarAluno" method="POST" action="">
            <input type="hidden" id="editAlunoId" name="id">

            <label>Nome:</label>
            <input type="text" id="editAlunoNome" name="nome" required>

            <label>Email:</label>
            <input type="email" id="editAlunoEmail" name="email" required>

            <label>CPF:</label>
            <input type="text" id="editAlunoCPF" name="cpf" required maxlength="14">

            <label>Plano:</label>
            <select id="editAlunoPlano" name="plano" required>
                <option value="">Selecione um plano</option>
                <option value="Mensal">Plano Básico</option>
                <option value="Trimestral">Plano Intermediário</option>
                <option value="Anual">Plano Premium</option>
            </select>

            <button type="submit" name="editar_aluno" class="add">Salvar Alterações</button>
        </form>
    </div>
</div>

    <!-- Professores -->
    <h2>Professores</h2>
    <button class="add" onclick="openModalProfessor()">+ Novo Professor</button>

    <table>
        <tr>
            <th>ID</th><th>Nome</th><th>CPF</th><th>Email</th><th>Especialidade</th><th>Ações</th>
        </tr>
        <?php while($prof = $result_professores->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($prof['id']) ?></td>
            <td><?= htmlspecialchars($prof['nome']) ?></td>
            <td><?= htmlspecialchars($prof['cpf']) ?></td>
            <td><?= htmlspecialchars($prof['email']) ?></td>
            <td><?= htmlspecialchars($prof['especialidade']) ?></td>
            <td>
                <button class="edit" onclick="openEditModalProfessor('<?= htmlspecialchars($prof['id']) ?>','<?= addslashes(htmlspecialchars($prof['nome'])) ?>','<?= addslashes(htmlspecialchars($prof['email'])) ?>','<?= addslashes(htmlspecialchars($prof['cpf'])) ?>','<?= addslashes(htmlspecialchars($prof['especialidade'])) ?>')">✏️ Editar</button>
                <a href="remover_professor.php?id=<?= $prof['id'] ?>" onclick="return confirm('Deseja realmente excluir este professor?')"><button class="delete">🗑️ Excluir</button></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Modal Editar Professor -->
<div id="modalEditarProfessor" class="modal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="tituloEditarProfessor">
        <span class="close" onclick="fecharModal('modalEditarProfessor')">&times;</span>
        <h2 id="tituloEditarProfessor">Editar Professor</h2>

        <form id="formEditarProfessor" method="POST" action="">
            <input type="hidden" id="editProfessorId" name="id">

            <label>Nome:</label>
            <input type="text" id="editProfessorNome" name="nome" required>

            <label>Email:</label>
            <input type="email" id="editProfessorEmail" name="email" required>

            <label>CPF:</label>
            <input type="text" id="editProfessorCPF" name="cpf" required maxlength="14">

            <label>Especialidade:</label>
            <input type="text" id="editProfessorEspecialidade" name="especialidade" required>

            <button type="submit" name="editar_professor" class="add">Salvar Alterações</button>
        </form>
    </div>
</div>

    <!-- Check-ins -->
    <h2>Check-ins</h2>
    <table>
        <tr>
            <th>ID Check-in</th><th>Aluno</th><th>Email</th><th>Plano</th><th>Data/Hora</th>
        </tr>
        <?php while($checkin = $result_checkins->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($checkin['checkin_id']) ?></td>
            <td><?= htmlspecialchars($checkin['name']) ?></td>
            <td><?= htmlspecialchars($checkin['email']) ?></td>
            <td><?= htmlspecialchars($checkin['plan']) ?></td>
            <td><?= htmlspecialchars($checkin['checkin_time']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Agendamentos -->
    <h2>Agendamentos</h2>
    <form method="POST" class="form-agendamento">
        <label>Aluno:</label>
        <select name="aluno_id" required>
            <option value="">Selecione</option>
            <?php
            $alunos_select = $conn->query("SELECT id, name FROM usuarios ORDER BY name ASC");
            while ($a = $alunos_select->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($a['id']) ?>"><?= htmlspecialchars($a['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Professor:</label>
        <select name="professor_id" required>
            <option value="">Selecione</option>
            <?php
            $prof_select = $conn->query("SELECT id, nome FROM professores ORDER BY nome ASC");
            while ($p = $prof_select->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($p['id']) ?>"><?= htmlspecialchars($p['nome']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Modalidade:</label>
        <select name="modalidade" required>
            <option value="">Selecione</option>
            <option>Funcional</option>
            <option>Crossfit</option>
            <option>Pilates</option>
            <option>Consulta com Personal</option>
            <option>Consulta com Nutricionista</option>
        </select>

        <label>Data:</label>
        <input type="date" name="data_aula" required>

        <label>Horário:</label>
        <input type="time" name="horario" required>

        <button type="submit" name="adicionar" class="add">Agendar Aula</button>
    </form>

    <table>
        <tr>
            <th>ID</th><th>Aluno</th><th>Professor</th><th>Modalidade</th><th>Data</th><th>Horário</th><th>Criado em</th><th>Ações</th>
        </tr>
        <?php while($ag = $result_agendamentos->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($ag['id']) ?></td>
            <td><?= htmlspecialchars($ag['aluno']) ?> (<?= htmlspecialchars($ag['aluno_cpf']) ?>)</td>
            <td><?= htmlspecialchars($ag['professor']) ?></td>
            <td><?= htmlspecialchars($ag['modalidade']) ?></td>
            <td><?= htmlspecialchars($ag['data_aula']) ?></td>
            <td><?= htmlspecialchars($ag['horario']) ?></td>
            <td><?= htmlspecialchars($ag['criado_em']) ?></td>
            <td>
                <button class="edit" onclick="openEditAgendamento('<?= htmlspecialchars($ag['id']) ?>','<?= addslashes(htmlspecialchars($ag['modalidade'])) ?>','<?= htmlspecialchars($ag['data_aula']) ?>','<?= htmlspecialchars($ag['horario']) ?>')">✏️ Editar</button>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($ag['id']) ?>">
                    <button type="submit" name="cancelar" class="delete" onclick="return confirm('Deseja realmente cancelar?')">🗑️ Cancelar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

<!-- Modais de cadastro -->

<!-- Modal Aluno Cadastro -->
<div id="modalAluno" class="modal" aria-hidden="true">
  <div class="modal-content">
    <span class="close" onclick="closeModalAluno()">&times;</span>
    <h2>Cadastrar Novo Aluno</h2>
    <form method="POST" action="cadastrar_aluno.php">
      <input type="text" name="nome" placeholder="Nome" required>
      <input type="text" name="cpf" placeholder="CPF (apenas números)" required maxlength="11">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <select name="plano" required>
        <option value="">Selecione um plano</option>
        <option value="Mensal">Plano Básico</option>
        <option value="Trimestral">Plano Intermediário</option>
        <option value="Anual">Plano Premium</option>
      </select>
      <button type="submit" class="add">Cadastrar</button>
    </form>
  </div>
</div>

<!-- Modal Professor Cadastro -->
<div id="modalProfessor" class="modal" aria-hidden="true">
  <div class="modal-content">
    <span class="close" onclick="closeModalProfessor()">&times;</span>
    <h2>Cadastrar Novo Professor</h2>
    <form method="POST" action="cadastrar_professor.php">
      <input type="text" name="nome" placeholder="Nome completo" required>
      <input type="text" name="cpf" placeholder="Digite seu CPF" required maxlength="11">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <input type="text" name="especialidade" placeholder="Especialidade" required>
      <button type="submit" class="add">Cadastrar Professor</button>
    </form>
  </div>
</div>

<!-- Modal Editar Agendamento -->
<div id="modalEditarAgendamento" class="modal" aria-hidden="true">
  <div class="modal-content">
    <span class="close" onclick="fecharModal('modalEditarAgendamento')">&times;</span>
    <h2>Editar Agendamento</h2>
    <form id="formEditarAgendamento" method="POST" action="">
        <input type="hidden" id="editAgendamentoId" name="id">
        <label>Modalidade:</label>
        <input type="text" id="editAgendamentoModalidade" name="modalidade" required>
        <label>Data:</label>
        <input type="date" id="editAgendamentoData" name="data_aula" required>
        <label>Horário:</label>
        <input type="time" id="editAgendamentoHorario" name="horario" required>
        <button type="submit" name="alterar" class="add">Salvar Alterações</button>
    </form>
  </div>
</div>

<script>
//abrir e fechar modais 
function openModalAluno() {
  openModalById('modalAluno');
}
function closeModalAluno() {
  closeModalById('modalAluno');
}
function openModalProfessor() {
  openModalById('modalProfessor');
}
function closeModalProfessor() {
  closeModalById('modalProfessor');
}

function openModalById(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.style.display = 'flex';
  setTimeout(() => modal.classList.add('show'), 10);
  modal.setAttribute('aria-hidden','false');
}
function closeModalById(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.classList.remove('show');
  setTimeout(() => modal.style.display = 'none', 250);
  modal.setAttribute('aria-hidden','true');
}

function fecharModal(id) { closeModalById(id); }

//Editar Aluno 
function openEditModalAluno(id, nome, email, cpf, plano) {
  document.getElementById('editAlunoId').value = id;
  document.getElementById('editAlunoNome').value = nome;
  document.getElementById('editAlunoEmail').value = email;
  document.getElementById('editAlunoCPF').value = cpf;
  document.getElementById('editAlunoPlano').value = plano;
  openModalById('modalEditarAluno');
}

//Editar Professor 
function openEditModalProfessor(id, nome, email, cpf, especialidade) {
  document.getElementById('editProfessorId').value = id;
  document.getElementById('editProfessorNome').value = nome;
  document.getElementById('editProfessorEmail').value = email;
  document.getElementById('editProfessorCPF').value = cpf;
  document.getElementById('editProfessorEspecialidade').value = especialidade;
  openModalById('modalEditarProfessor');
}

//Editar Agendamento 
function openEditAgendamento(id, modalidade, data, horario) {
  document.getElementById('editAgendamentoId').value = id;
  document.getElementById('editAgendamentoModalidade').value = modalidade;
  document.getElementById('editAgendamentoData').value = data;
  document.getElementById('editAgendamentoHorario').value = horario;
  openModalById('modalEditarAgendamento');
}

//Fechar quando clicar fora ou pressionar ESC
window.addEventListener('click', function(e) {
  document.querySelectorAll('.modal.show').forEach(modal => {
    if (e.target === modal) {
      closeModalById(modal.id);
    }
  });
});
window.addEventListener('keydown', function(e) {
  if (e.key === "Escape") {
    document.querySelectorAll('.modal.show').forEach(modal => closeModalById(modal.id));
  }
});


//filtro de alunos por CPF
function filtrarAlunos() {
  const filtro = document.getElementById('filtro-aluno').value.toLowerCase();

  //Seleciona apenas a tabela dos alunos
  const tabela = document.querySelector(".container table");
  const linhas = tabela.querySelectorAll("tr");

  linhas.forEach((tr, idx) => {
    if (idx === 0) return; 

    const cpf = tr.children[2]?.innerText.toLowerCase(); 

    if (!cpf) return;

    tr.style.display = cpf.includes(filtro) ? "" : "none";
  });
}

</script>

</body>
</html>
