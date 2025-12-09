<?php
session_start();
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST["nome"] ?? '';
    $cpf = $_POST["cpf"] ?? '';
    $email = $_POST["email"] ?? '';
    $senha = $_POST["senha"] ?? '';
    $plano = $_POST["plano"] ?? '';

    $nome_cartao = $_POST['nome_cartao'] ?? '';
    $numero_cartao = $_POST['numero_cartao'] ?? '';
    $validade = $_POST['validade'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    if(empty($nome) || empty($cpf) || empty($email) || empty($senha) || empty($plano) || empty($nome_cartao) || empty($numero_cartao) || empty($validade) || empty($cvv)){
        $erro = "Todos os campos são obrigatórios.";
    } else {
        // verifica se o email existe
        $stmtCheck = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        // verifica se o CPF existe
        $stmtCheckCpf = $conn->prepare("SELECT id FROM usuarios WHERE cpf=?");
        $stmtCheckCpf->bind_param("s", $cpf);
        $stmtCheckCpf->execute();
        $resultCheckCpf = $stmtCheckCpf->get_result();

        if($resultCheck->num_rows > 0){
            $erro = "Este e-mail já está cadastrado.";
        } elseif($resultCheckCpf->num_rows > 0){
            $erro = "Este CPF já está cadastrado.";
        } else {
            // cadastra o usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (name, cpf, email, password, plan) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nome, $cpf, $email, $senha_hash, $plano);

            if($stmt->execute()){
                $user_id = $stmt->insert_id;

                $valor = 0; 
                $data_pagamento = date("Y-m-d");
                $status = "PAGO";
                $metodo = "cartao";

                $stmtPay = $conn->prepare("INSERT INTO pagamentos (user_id, plan, valor, data_pagamento, status, metodo) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtPay->bind_param("isdsss", $user_id, $plano, $valor, $data_pagamento, $status, $metodo);
                $stmtPay->execute();
                $stmtPay->close();

                // redireciona para login após o cadastro e pagamento
                header("Location: login.php");
                exit;
            } else {
                $erro = "Erro ao cadastrar usuário.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro e Pagamento - NextLevelGym</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/cadastro.css">
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>

<body>
<div class="container">
  <div class="form-container">
    <!-- Botão X para voltar -->
   <button class="btn-close" onclick="window.history.back()">X</button>

    <h2>Cadastro e Pagamento</h2>

    <?php if(!empty($erro)): ?>
        <p class="msg" style="color:red;"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <h3>Dados do Usuário</h3>
        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="text" name="cpf" placeholder="CPF " required maxlength="11">
        <input type="email" name="email" placeholder="Email" required autocomplete="off">
        <input type="password" name="senha" placeholder="Senha" required autocomplete="new-password">
        <select name="plano" required>
            <option value="">Selecione um plano</option>
            <option value="Básico">Plano Básico</option>
            <option value="Intermediário">Plano Intermediário</option>
            <option value="Premium">Plano Premium</option>
        </select>

        <h3>Pagamento - Cartão de Crédito</h3>
        <input type="text" name="nome_cartao" placeholder="Nome escrito no cartão" required>
        <input type="text" name="numero_cartao" placeholder="0000 0000 0000 0000" required>
        <input type="text" name="validade" placeholder="MM/AA" required>
        <input type="number" name="cvv" placeholder="CVV" required>

        <button type="submit">Cadastrar e Pagar</button>

    </form>
  </div>
</div>

</body>
</html>
