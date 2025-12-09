<?php
require_once("conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $aluno_id = $_POST["aluno_id"];
    $modalidade = $_POST["modalidade"];
    $data_aula = $_POST["data_aula"];
    $horario = $_POST["horario"];

    // 1) Verificar quantos já estão agendados
    $sqlCount = "SELECT COUNT(*) AS total 
                 FROM agendamentos 
                 WHERE modalidade = ? AND data_aula = ? AND horario = ?";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param("sss", $modalidade, $data_aula, $horario);
    $stmtCount->execute();
    $result = $stmtCount->get_result();
    $row = $result->fetch_assoc();
    $inscritos = $row["total"];

    // 2) Consultar limite de vagas dessa modalidade
    $sqlLimite = "SELECT limite_vagas FROM modalidades WHERE nome = ?";
    $stmtLimite = $conn->prepare($sqlLimite);
    $stmtLimite->bind_param("s", $modalidade);
    $stmtLimite->execute();
    $result2 = $stmtLimite->get_result();
    $row2 = $result2->fetch_assoc();

    $limite = $row2["limite_vagas"];

    // 3) Se estiver cheio → bloquear
    if ($inscritos >= $limite) {
        header("Location: admin.php?msg=lotado");
        exit;
    }

    // 4) Caso tenha vaga → agenda
    $sql = "INSERT INTO agendamentos (aluno_id, modalidade, data_aula, horario)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $aluno_id, $modalidade, $data_aula, $horario);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=agendado");
        exit;
    } else {
        echo "Erro ao agendar: " . $conn->error;
    }
}
?>
