<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Planos - Next Level Gym</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/plano.css">
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
            <a href="#about">Sobre</a>
            <a href="servicos.php">Nossos Serviços</a>
            <a href="plano.php"> Planos</a>
            <a href="trainers">Quero ser Aluno</a>
          <a href="login.php">Área do Usuário</a>
        </nav>

    </header>
    
 <!-- planos -->
<section class="section" id="planos">
  <h2 class="titulo-planos">Nossos Planos</h2>
  <p class="subtitulo-planos">Escolha o ideal para sua rotina e objetivos</p>

  <div class="planos-container">

    <!-- Plano Básico -->
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
      <form action="contratar.php" method="post">
        <input type="hidden" name="plano" value="Básico">
        <button type="submit" class="btn-contratar">Matricule-se</button>
      </form>
    </div>

    <!-- Plano Intermediário -->
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
        <li>✔ Leve 5 amigos com você por mês</li>
      </ul>
      <form action="contratar.php" method="post">
        <input type="hidden" name="plano" value="Intermediário">
        <button type="submit" class="btn-contratar destaque-btn">Matricule-se</button>
      </form>
    </div>

    <!-- Plano Premium -->
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
      <form action="contratar.php" method="post">
        <input type="hidden" name="plano" value="Premium">
        <button type="submit" class="btn-contratar">Matricule-se</button>
      </form>
    </div>

  </div>
</section>

<footer class="footer">
    <div class="box-container">
        <div class="box">
            <h3>Links rápidos</h3>
            <a class="links" href="#home">Início</a>
            <a class="links" href="#about">Sobre</a>
            <a class="links" href="#features">Características</a>
            <a class="links" href="#pricing">Valores</a>
            <a class="links" href="#trainers">Treinadores</a>
            <a class="links" href="#blogs">blogs</a>
        </div>
        <div class="box">
            <h3>Horário de Funcionamento</h3>
            <p> Segunda : <i> 7:00 - 22:00 </i> </p>
            <p> Terça : <i> 7:00 - 22:00 </i> </p>
            <p> Quarta : <i> 7:00 - 22:00 </i> </p>
            <p> Quinta : <i> 7:00 - 22:00 </i> </p>
            <p> Sexta : <i> 7:00 - 22:00 </i> </p>
            <p> Sábado : <i> 7:00am - 10:30 </i> </p>
            <p> Domingo : <i> Fechado </i> </p>
        </div>
        <div class="box">
            <h3>Nossos Contatos</h3>
            <p> <i class="fas fa-phone"></i> +21 8222-3333 </p>
            <p> <i class="fas fa-envelope"></i> nextlevelgym@gmail.com </p>
            <p> <i class="fas fa-map"></i> Rio de Janeiro, Brazil</p>
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-linkedin"></a>
                <a href="#" class="fab fa-pinterest"></a>
            </div>
        </div>
        <div class="box">
            <h3>Novidades</h3>
            <p>Se inscreva para receber as novidades</p>
            <form action="">
                <input type="email" name="" class="email" placeholder="digite seu e-mail" id="">
                <input type="submit" value="subscribe" class="btn">
            </form>
        </div>
    </div>
</footer>


</body>
</html>
