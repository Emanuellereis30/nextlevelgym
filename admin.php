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

// CRUD Agendamentos
if(isset($_POST['adicionar'])){
    $aluno_id = $_POST['aluno_id'];
    $modalidade = $_POST['modalidade'];
    $data_aula = $_POST['data_aula'];
    $horario = $_POST['horario'];

    $stmt = $conn->prepare("INSERT INTO agendamentos (aluno_id, modalidade, data_aula, horario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $aluno_id, $modalidade, $data_aula, $horario);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
}

if(isset($_POST['alterar'])){
    $id = $_POST['id'];
    $modalidade = $_POST['modalidade'];
    $data_aula = $_POST['data_aula'];
    $horario = $_POST['horario'];

    $sql = "UPDATE agendamentos SET 
            modalidade = COALESCE(NULLIF('$modalidade',''), modalidade),
            data_aula = COALESCE(NULLIF('$data_aula',''), data_aula),
            horario = COALESCE(NULLIF('$horario',''), horario)
            WHERE id = $id";
    $conn->query($sql);
    header("Location: ".$_SERVER['PHP_SELF']);
}

if(isset($_POST['cancelar'])){
    $id = $_POST['id'];
    $conn->query("DELETE FROM agendamentos WHERE id = $id");
    header("Location: ".$_SERVER['PHP_SELF']);
}

// Consulta agendamentos
$sql_agendamentos = "
SELECT a.id, u.name AS aluno, a.modalidade, a.data_aula, a.horario, a.criado_em
FROM agendamentos a
JOIN usuarios u ON a.aluno_id = u.id
ORDER BY a.data_aula DESC";
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
    <input type="text" id="filtro-aluno" placeholder="Pesquisar por nome..." onkeyup="filtrarAlunos()">
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

    <!-- Form adicionar -->
    <form method="POST" class="form-agendamento">
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

        <button type="submit" name="adicionar" class="add">Agendar Aula</button>
    </form>

    <!-- Tabela de agendamentos -->
    <table>
        <tr>
            <th>ID</th>
            <th>Aluno</th>
            <th>Modalidade</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
        <?php while($ag = $result_agendamentos->fetch_assoc()): ?>
        <tr>
            <td><?= $ag['id'] ?></td>
            <td><?= $ag['aluno'] ?></td>
            <td><?= $ag['modalidade'] ?></td>
            <td><?= $ag['data_aula'] ?></td>
            <td><?= $ag['horario'] ?></td>
            <td><?= $ag['criado_em'] ?></td>
            <td>
                <button class="edit" onclick="openEditAgendamento('<?= $ag['id'] ?>','<?= addslashes($ag['modalidade']) ?>','<?= $ag['data_aula'] ?>','<?= $ag['horario'] ?>')">✏️ Editar</button>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $ag['id'] ?>">
                    <button type="submit" name="cancelar" class="delete" onclick="return confirm('Deseja realmente cancelar?')">🗑️ Cancelar</button>
                </form>
            </td>
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

<!-- Modal Editar Agendamento -->
<div id="modal-edit-agendamento" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditAgendamento()">&times;</span>
        <h2>Editar Agendamento</h2>
        <form method="POST">
            <input type="hidden" name="id" id="edit-id">
            <label>Modalidade:</label>
            <input type="text" name="modalidade" id="edit-modalidade">
            <label>Data:</label>
            <input type="date" name="data_aula" id="edit-data">
            <label>Horário:</label>
            <input type="time" name="horario" id="edit-horario">
            <button type="submit" name="alterar" class="add">Salvar Alterações</button>
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

function openEditAgendamento(id, modalidade, data, horario){
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-modalidade').value = modalidade;
    document.getElementById('edit-data').value = data;
    document.getElementById('edit-horario').value = horario;

    const modal = document.getElementById('modal-edit-agendamento');
    modal.style.display = 'block';
    setTimeout(() => modal.classList.add('show'), 10);
}

function closeEditAgendamento(){
    const modal = document.getElementById('modal-edit-agendamento');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = "none", 300);
}

window.onclick = function(event) {
    const modalAluno = document.getElementById('modal');
    const modalAgendamento = document.getElementById('modal-edit-agendamento');
    if(event.target == modalAluno) closeModal();
    if(event.target == modalAgendamento) closeEditAgendamento();
}



function filtrarAlunos() {
    const input = document.getElementById('filtro-aluno');
    const filter = input.value.toLowerCase();
    const table = document.querySelector('.container table'); // pega a primeira tabela (Alunos)
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) { // começa em 1 para pular o cabeçalho
        const td = tr[i].getElementsByTagName('td')[1]; // coluna do nome
        if (td) {
            const txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toLowerCase().includes(filter) ? "" : "none";
        }
    }
}

</script>

</body>
</html>
