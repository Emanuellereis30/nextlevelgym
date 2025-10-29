<?php
require_once("conexao.php");

// Alunos
$sql_alunos = "SELECT * FROM usuarios ORDER BY id DESC";
$result_alunos = $conn->query($sql_alunos);

// Check-ins
$sql_checkins = "
    SELECT c.id AS checkin_id, u.name, u.email, u.plan, c.checkin_time
    FROM checkins c
    JOIN usuarios u ON c.usuarios_id = u.id
    ORDER BY c.checkin_time DESC
";
$result_checkins = $conn->query($sql_checkins);

// Agendamentos
$sql_agendamentos = "
    SELECT a.id, u.name AS aluno, a.modalidade, a.data_aula, a.horario, a.criado_em
    FROM agendamentos a
    JOIN usuarios u ON a.aluno_id = u.id
    ORDER BY a.data_aula DESC
";
$result_agendamentos = $conn->query($sql_agendamentos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel do Administrador</title>
<link rel="stylesheet" href="css/admin.css">
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="index.php"><img class="logo" src="img/logopng.png" alt="Logo"></a>
    <h1>Painel do Administrador</h1>
</div>

<div class="container">

    <!-- Alunos -->
    <h2>Alunos</h2>
    <button class="add" onclick="openModal()">+ Novo Aluno</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Plano</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
        <?php while($aluno = $result_alunos->fetch_assoc()): ?>
        <tr>
            <td><?= $aluno['id'] ?></td>
            <td><?= $aluno['name'] ?></td>
            <td><?= $aluno['email'] ?></td>
            <td><?= $aluno['plan'] ?></td>
            <td><?= $aluno['created_at'] ?></td>
            <td>
                <button class="edit" 
                    onclick="openEditModal('<?= $aluno['id'] ?>', '<?= addslashes($aluno['name']) ?>', '<?= $aluno['email'] ?>', '<?= $aluno['plan'] ?>')">
                    ✏️ Editar
                </button>
                <a href="removeraluno.php?id=<?= $aluno['id'] ?>" onclick="return confirm('Deseja realmente excluir?')">
                    <button class="delete">🗑️ Excluir</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Check-ins -->
    <h2>Check-ins</h2>
    <table>
        <tr>
            <th>ID Check-in</th>
            <th>Aluno</th>
            <th>Email</th>
            <th>Plano</th>
            <th>Data/Hora</th>
        </tr>
        <?php while($checkin = $result_checkins->fetch_assoc()): ?>
        <tr>
            <td><?= $checkin['checkin_id'] ?></td>
            <td><?= $checkin['name'] ?></td>
            <td><?= $checkin['email'] ?></td>
            <td><?= $checkin['plan'] ?></td>
            <td><?= $checkin['checkin_time'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Agendamentos -->
    <h2>Agendamentos</h2>
    <form method="POST" action="agendar_aula.php" class="form-agendamento">
        <label for="aluno_id">Aluno:</label>
        <select name="aluno_id" required>
            <option value="">Selecione o aluno</option>
            <?php
            $alunos_select = $conn->query("SELECT id, name FROM usuarios ORDER BY name ASC");
            while ($a = $alunos_select->fetch_assoc()):
            ?>
                <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="modalidade">Modalidade:</label>
        <select name="modalidade" required>
            <option value="">Selecione</option>
            <option>Funcional</option>
            <option>Crossfit</option>
            <option>Pilates</option>
            <option>Consulta com Personal</option>
            <option>Consulta com Nutricionista</option>
        </select>

        <label for="data_aula">Data:</label>
        <input type="date" name="data_aula" required>

        <label for="horario">Horário:</label>
        <input type="time" name="horario" required>

        <button type="submit" class="add">Agendar Aula</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Aluno</th>
            <th>Modalidade</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Criado em</th>
        </tr>
        <?php while($ag = $result_agendamentos->fetch_assoc()): ?>
        <tr>
            <td><?= $ag['id'] ?></td>
            <td><?= $ag['aluno'] ?></td>
            <td><?= $ag['modalidade'] ?></td>
            <td><?= $ag['data_aula'] ?></td>
            <td><?= $ag['horario'] ?></td>
            <td><?= $ag['criado_em'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

<!-- Modal de alunos -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modal-title">Cadastrar Novo Aluno</h2>
        <form id="modal-form" method="POST" action="cadastrar_aluno.php">
            <input type="hidden" name="id" id="aluno-id">
            <input type="text" name="name" id="aluno-name" placeholder="Nome" required>
            <input type="email" name="email" id="aluno-email" placeholder="Email" required>
            <input type="password" name="password" id="aluno-password" placeholder="Senha">
            <select name="plan" id="aluno-plan" required>
                <option value="">Selecione um plano</option>
                <option value="Mensal">Plano Básico</option>
                <option value="Trimestral">Plano Intermediário</option>
                <option value="Anual">Plano Premium</option>
            </select>
            <button type="submit" class="add" id="modal-submit">Cadastrar</button>
        </form>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('modal');
    const title = document.getElementById('modal-title');
    const form = document.getElementById('modal-form');
    const submitBtn = document.getElementById('modal-submit');

    document.getElementById('aluno-id').value = '';
    document.getElementById('aluno-name').value = '';
    document.getElementById('aluno-email').value = '';
    document.getElementById('aluno-password').value = '';
    document.getElementById('aluno-plan').value = '';

    title.textContent = 'Cadastrar Novo Aluno';
    submitBtn.textContent = 'Cadastrar';
    form.action = 'cadastrar_aluno.php';
    modal.style.display = "block";
    setTimeout(() => modal.classList.add('show'), 10);
}

function openEditModal(id, name, email, plan) {
    const modal = document.getElementById('modal');
    const title = document.getElementById('modal-title');
    const form = document.getElementById('modal-form');
    const submitBtn = document.getElementById('modal-submit');

    document.getElementById('aluno-id').value = id;
    document.getElementById('aluno-name').value = name;
    document.getElementById('aluno-email').value = email;
    document.getElementById('aluno-plan').value = plan;
    document.getElementById('aluno-password').value = '';

    title.textContent = 'Editar Aluno';
    submitBtn.textContent = 'Salvar Alterações';
    form.action = 'editar_aluno.php';
    modal.style.display = "block";
    setTimeout(() => modal.classList.add('show'), 10);
}

function closeModal() {
    const modal = document.getElementById('modal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = "none", 300);
}

window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target == modal) closeModal();
}
</script>

</body>
</html>
