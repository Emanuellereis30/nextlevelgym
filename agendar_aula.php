<?php
require_once("conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $aluno_id = $_POST["aluno_id"];
    $modalidade = $_POST["modalidade"];
    $data_aula = $_POST["data_aula"];
    $horario = $_POST["horario"];

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
