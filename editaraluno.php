<?php
require_once("conexao.php");

if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $res = $conn->query("SELECT * FROM usuarios WHERE id=$id");
    $aluno = $res->fetch_assoc();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $plan = $_POST["plan"];
    $conn->query("UPDATE usuarios SET name='$name', email='$email', plan='$plan' WHERE id=$id");
    header("Location: admin.php");
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
    <input type="text" name="name" value="<?= $aluno['name'] ?>" required><br><br>
    <input type="email" name="email" value="<?= $aluno['email'] ?>" required><br><br>
    <select name="plan" required>
        <option value="Mensal" <?= $aluno['plan']=='Mensal'?'selected':'' ?>>Mensal</option>
        <option value="Trimestral" <?= $aluno['plan']=='Trimestral'?'selected':'' ?>>Trimestral</option>
        <option value="Anual" <?= $aluno['plan']=='Anual'?'selected':'' ?>>Anual</option>
    </select><br><br>
    <button type="submit">Salvar</button>
</form>
<a href="admin.php">Voltar</a>
</body>
</html>
