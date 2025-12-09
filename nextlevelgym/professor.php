<?php
session_start();
include('conexao.php');


if(isset($_GET['logout'])){
    session_destroy();
    header("Location: loginprofessor.php");
    exit;
}


if (!isset($_SESSION['professor_id'])) {
    header("Location: loginprofessor.php");
    exit;
}

$professor_id = $_SESSION['professor_id'];

// consulta todas as aulas agendadas do professor
$sql = "SELECT a.modalidade, a.data_aula, a.horario, u.name AS aluno_name
        FROM agendamentos a
        INNER JOIN usuarios u ON a.aluno_id = u.id
        WHERE a.professor_id = ?
        ORDER BY a.data_aula, a.horario, a.modalidade";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$result = $stmt->get_result();

// junta os alunos por turma 
$turmas = [];
while($row = $result->fetch_assoc()){
    $key = $row['modalidade'] . '|' . $row['data_aula'] . '|' . $row['horario'];
    if(!isset($turmas[$key])){
        $turmas[$key] = [
            'modalidade' => $row['modalidade'],
            'data' => $row['data_aula'],
            'horario' => $row['horario'],
            'alunos' => []
        ];
    }
    $turmas[$key]['alunos'][] = $row['aluno_name'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel do Professor</title>
<link rel="stylesheet" href="css/painel_professor.css">
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>
<body>

<div class="navbar">
    <a href="?logout=true"><img src="img/logopng.png" class="logo" alt="Next Level Logo"></a>
    <h1>Painel do Professor</h1>
</div>

<h2>Minhas Aulas Agendadas</h2>

<div class="aula-section">
    <?php if(empty($turmas)): ?>
        <p class="no-aulas">Nenhuma aula agendada.</p>
    <?php else: ?>
        <?php foreach($turmas as $turma): ?>
            <div class="aula-card">
                <h3><?= htmlspecialchars($turma['modalidade']) ?></h3>
                <p>Data: <?= htmlspecialchars($turma['data']) ?></p>
                <p>Horário: <?= htmlspecialchars($turma['horario']) ?></p>
                <p>Alunos:</p>
                <ul>
                    <?php foreach($turma['alunos'] as $aluno): ?>
                        <li><?= htmlspecialchars($aluno) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
