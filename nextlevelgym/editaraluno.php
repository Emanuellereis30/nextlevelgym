<?php
require_once("conexao.php");

$id = $_GET["id"] ?? null;

if (!$id) {
    die("ID inválido!");
}

// Busca aluno pelo ID
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$aluno = $result->fetch_assoc();

if (!$aluno) {
    die("Aluno não encontrado!");
}

// Atualiza cadastro
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? '';
    $email = $_POST["email"] ?? '';
    $plan = $_POST["plan"] ?? '';

    $stmtUpdate = $conn->prepare("UPDATE usuarios SET name=?, email=?, plan=? WHERE id=?");
    $stmtUpdate->bind_param("sssi", $name, $email, $plan, $id);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Aluno atualizado com sucesso!'); window.location='admin.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao atualizar aluno!'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
</head>
<body>

<h2>Editar Aluno</h2>

<form method="POST">
    <label>Nome:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($aluno['name']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($aluno['email']) ?>" required><br><br>

    <label>Plano:</label><br>
    <select name="plan" required>
        <option value="Mensal" <?= $aluno['plan']=='Mensal'?'selected':'' ?>>Mensal</option>
        <option value="Trimestral" <?= $aluno['plan']=='Trimestral'?'selected':'' ?>>Trimestral</option>
        <option value="Anual" <?= $aluno['plan']=='Anual'?'selected':'' ?>>Anual</option>
    </select><br><br>

    <button type="submit">Salvar</button>
</form>

<br>
<a href="admin.php">Voltar</a>

</body>
</html>
