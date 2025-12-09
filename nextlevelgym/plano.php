<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Planos - Next Level Gym</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/plano.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>
<body>
        <header class="header">
       <a href="index.php" class="logo">
          <img src="img/logopng.png" alt="Logo AutoFit">
       </a>


        <div id="menu-btn" class="fas fa-bars"></div>

        <nav class="navbar">
            <a href="index.php">Início </a>
            <a href="sobre.php">Sobre</a>
            <a href="plano.php"> Planos</a>
            <a href="cadastro.php">Quero ser Aluno</a>
          <a href="admin.php">Área do Usuário</a>
        </nav>

    </header>
    

<section class="section" id="planos">
  <h2 class="titulo-planos">Nossos Planos</h2>
  <p class="subtitulo-planos">Escolha o ideal para sua rotina e objetivos</p>

  <div class="planos-container">

    
    <div class="plano-card">
      <div class="plano-topo basico">
        <h3>Básico</h3>
          <h6>Nosso plano mais econômico para você se exercitar quando e quanto quiser na academia que você escolher.</h6>
        <p class="preco">
           <span class="moeda">R$</span>
           <span class="valor">99</span>
           <span class="centavos">,90</span>
           <span class="mes">/Mês</span>
        </p>
      </div>
      <ul>
        <li>✔ Acesso em horário comercial</li>
        <li>✔ Aparelhos modernos</li>
        <li>✔ 1 aula coletiva/semana</li>
        <li>✔ Acesso a uma unidade</li>
      </ul>
      <form action="cadastro.php" method="post">
        <input type="hidden" name="plano" value="Básico">
        <button type="submit" class="btn-contratar">Matricule-se</button>
      </form>
    </div>

   
    <div class="plano-card destaque">
      <div class="plano-topo intermediario">
        <h3>Intermediário</h3>
        <h6>Nosso plano mensal para você que quer ter mais flexibilidade, e quer treinar em uma academia de alto padrão.</h6>
        <p class="preco">
            <span class="moeda">R$</span>
            <span class="valor">139</span>
            <span class="centavos">,90</span>
            <span class="mes">/Mês</span>
        </p>

        <span class="badge">Mais Popular</span>
      </div>
      <ul>
        <li>✔ Acesso ilimitado</li>
        <li>✔ Personal 1x/semana</li>
        <li>✔ 3 aulas coletivas/semana</li>
        <li>✔ Consulta com Nutricionista</li>
        <li>✔ Acesso a todas as unidades do seu estado.</li>
        <li>✔ Leve 3 amigos com você por mês</li>
      </ul>
      <form action="cadastro.php" method="post">
        <input type="hidden" name="plano" value="Intermediário">
        <button type="submit" class="btn-contratar destaque-btn">Matricule-se</button>
      </form>
    </div>

   
    <div class="plano-card">
      <div class="plano-topo premium">
        <h3>Premium</h3>
        <h6>Treine em qualquer academmia Next Level, em todo território brasileiro!</h6>
        <p class="preco">
             <span class="moeda">R$</span>
             <span class="valor">249</span>
             <span class="centavos">,90</span>
             <span class="mes">/Mês</span>
        </p>
      </div>
      <ul>
        <li>✔ Acesso 24h</li>
        <li>✔ Personal ilimitado</li>
        <li>✔ Cadeira de massagem</li>
        <li>✔ Todas as aulas coletivas</li>
        <li>✔ Leve 6 amigos com você por mês</li>
        <li>✔ Consulta com Nutricionista</li>
        <li>✔ Área VIP com vestiário</li>
        <li>✔ Acesso a todas as unidades do Brasil</li>
      </ul>
      <form action="cadastro.php" method="post">
        <input type="hidden" name="plano" value="Premium">
        <button type="submit" class="btn-contratar">Matricule-se</button>
      </form>
    </div>

  </div>
</section>

<footer class="footer">
    <div class="box-container">

        <div class="box">
            <h3>Links Institucionais</h3>
            <a class="links" href="#home">Início</a>
            <a class="links" href="sobre.php">Sobre</a>
            <a class="links" href="plano.php">Planos </a>
            <a class="links" href="#trainers">Equipe Técnica</a>
           
        </div>

        <div class="box">
            <h3>Horário de Funcionamento</h3>
            <p>Segunda a Sexta: <i>07h00 — 22h00</i></p>
            <p>Sábado: <i>07h00 — 12h00</i></p>
            <p>Domingo: <i>Fechado</i></p>
        </div>

        <div class="box">
            <h3>Contato Corporativo</h3>
            <p><i class="fas fa-phone"></i> +55 21 98222-3333</p>
            <p><i class="fas fa-envelope"></i> nextlevelgym@gmail.com</p>
            <p><i class="fas fa-map"></i> Rio de Janeiro — Brasil</p>

            <div class="share">
                <a href="#" class="fab fa-facebook-f" aria-label="Facebook"></a>
                <a href="#" class="fab fa-twitter" aria-label="Twitter"></a>
                <a href="#" class="fab fa-linkedin" aria-label="LinkedIn"></a>
            </div>
        </div>

    </div>
</footer>


</body>
</html>
