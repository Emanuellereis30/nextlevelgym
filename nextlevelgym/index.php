<?php
include "conexao.php"; 
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextLevelGym</title>
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
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
            <a href="login.php">Área do Usuário</a>
        </nav>

    </header>

</body>
<main>
<section id="home" class="home">
    <div class="swiper home-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide slide" style="background: url(../nextlevelgym/img/academiabaner.png) no-repeat;">
                <div class="content">
                    <span>Treine. Supere. Conquiste.</span>
                    <h3>Torne-se mais forte do que suas desculpas.</h3>
                    <a href="cadastro.php" class="btn">Iniciar</a>
                </div>
            </div>

            <div class="swiper-slide slide" style="background: url(../nextlevelgym/img/homemtreino.jpg) no-repeat;">
                <div class="content">
                    <span>Treine. Supere. Conquiste.</span>
                    <h3>Torne-se mais forte do que suas desculpas.</h3>
                    <a href="cadastro.php" class="btn">Iniciar</a>
                </div>
            </div>

            <div class="swiper-slide slide" style="background: url(../nextlevelgym/img/musculosa1.jpg) no-repeat;">
                <div class="content">
                    <span>Treine. Supere. Conquiste.</span>
                    <h3>Torne-se mais forte do que suas desculpas.</h3>
                    <a href="cadastro.php" class="btn">Iniciar</a>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

</section>


<section id="about" class="about">
    <div class="image">
        <img src="img/sobre.jpeg" alt="">
    </div>

    <div class="content">
        <span>Sobre Nós</span>
        <h3 class="title">Cada dia é uma chance de se tornar melhor</h3>
        <p>Escrever Algo Sobre</p>

        <div class="box-container">
            <div class="box">
                <h3><i class="fas fa-check"></i>Corpo e Mente</h3>
                <p>Treine seu corpo e sua mente será treinada</p>
            </div>

            <div class="box">
                <h3><i class="fas fa-check"></i>Vida Saudável</h3>
                <p>Conquiste uma vida saudável</p>
            </div>
            <div class="box">
                <h3><i class="fas fa-check"></i>Estratégias</h3>
                <p>Estratégias para seu treinamento</p>
            </div>
            <div class="box">
                <h3><i class="fas fa-check"></i>Treino</h3>
                <p>Treine seu corpo fortemente</p>
            </div>
        </div>
        <a href="sobre.php" class="btn">Ler Mais</a>
    </div>
</section>
  

<section id="features" class="features">
   
    <h1 class="heading"> <span>Benefícios de Cuidar da Saúde</span> </h1>

    <div class="box-container">
        <div class="box">
            <div class="image">
                <img src="img/casalacademia.jpg" alt="">
            </div>
            <div class="content">
                <img src="https://i.postimg.cc/J46txFdD/icon-1.png" alt="">
                <h3>Musculação</h3>
                <p>A musculação não é só sobre estética é sobre força, resistência e superação pessoal.</p>
                <a href="https://www.tuasaude.com/beneficios-da-musculacao/" class="btn" target="_blank">Ler Mais</a>
            </div>
        </div>

        <div class="box second">
            <div class="image">
                <img src="img/homemmalhando.jpg" alt="">
            </div>
            <div class="content">
                <img src="https://i.postimg.cc/5N3KRBwF/icon-2.png" alt="">
                <h3>Homens</h3>
                <p>Para os homens, cuidar da saúde é essencial para garantir energia, desempenho e bem-estar. </p>
                <a href="https://www.terra.com.br/vida-e-estilo/saude/treinos-para-homens-a-importancia-dos-exercicios-para-a-saude-masculina,c861793e96d7566b28985931b6bb659emh8fab5y.html" class="btn" target="_blank">Ler Mais</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="img/mulhertreino.jpg" alt="">
            </div>
            <div class="content">
                <img src="https://i.postimg.cc/pTjkP83x/icon-3.png" alt="">
                <h3>Mulheres</h3>
                <p>A prática regular de exercícios ajuda a equilibrar corpo e mente, melhora a autoestima e contribui para uma vida mais saudável.</p>
                <a href="https://ge.globo.com/eu-atleta/noticia/musculacao-feminina-emagrece-e-garante-beneficios-para-a-saude-da-mulher.ghtml" class="btn" target="_blank">Ler Mais</a>
            </div>
        </div>

    </div>
</section>

<section id="trainers" class="trainers">
    <h1 class="heading"> <span>Nossos Profissionais</span> </h1>
    <div class="box-container">

        <div class="box">
            <img src="img/personal2.jpeg" alt="">
            <div class="content">
                <span>Personal</span>
                <h3>João Paulo</h3>
                <div class="share">
                    <a href="#" class="fab fa-instagram"></a>
                </div>
            </div>
        </div>


        <div class="box">
            <img src="img/nutri_homem.jpg" alt="">
            <div class="content">
                <span>Nutricionista</span>
                <h3>Renato Souza</h3>
                <div class="share">
                    <a href="#" class="fab fa-instagram"></a>
                </div>
            </div>
        </div>

        <div class="box">
            <img src="img/Personalmulher1.jpeg" alt="">
            <div class="content">
                <span>Personal</span>
                <h3>Joice Alves</h3>
                <div class="share">
                    <a href="#" class="fab fa-instagram"></a>
                </div>
            </div>
        </div>

        <div class="box">
            <img src="img/nutri.jpg" alt="">
            <div class="content">
                <span>Nutricionista</span>
                <h3>Luiza Botelho</h3>
                <div class="share">
                    <a href="#" class="fab fa-instagram"></a>
                </div>
            </div>
        </div>

    </div>

</section>


<section class="banner">
    <h4>faça parte do nosso time!</h4>
    <h2>Mais força, mais saúde, mais você! Inscreva-se hoje e transforme sua rotina.</h2>
    <a href="plano.php" class="btn">Saiba mais</a>
</section>


<section class="review">
    <div class="information">
        <span>Feedbacks</span>
        <h3>O que nossos clientes dizem</h3>
        <p>Quer saber o que nossos clientes falam sobre nós?</p>
        <a href="#" class="btn">Leia Todos</a>
    </div>
    <div class="swiper review-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide slide">
                <p>Melhor academia da cidade</p>
                <div class="user">
                    <img src="https://i.postimg.cc/4xhDF81N/pic-1.png" alt="">
                    <div class="info">
                        <h3>João Luiz</h3>
                        <span>designer</span>
                    </div>
                    <i class="fas fa-quote-left"></i>
                </div>
            </div>

            <div class="swiper-slide slide">
                <p>O melhor atendimento que eu já tive</p>
                <div class="user">
                    <img src="https://i.postimg.cc/ry7XCXSY/pic-2.png" alt="">
                    <div class="info">
                        <h3>Luana</h3>
                        <span>designer</span>
                    </div>
                    <i class="fas fa-quote-left"></i>
                </div>
            </div>

            <div class="swiper-slide slide">
                <p>Equipamentos novos e fáceis de serem utilizados</p>
                <div class="user">
                    <img src="https://i.postimg.cc/7ZxBSmQW/pic-3.png" alt="">
                    <div class="info">
                        <h3>john deo</h3>
                        <span>designer</span>
                    </div>
                    <i class="fas fa-quote-left"></i>
                </div>
            </div>

            <div class="swiper-slide slide">
                <p>Os melhores personais estão aqui</p>
                <div class="user">
                    <img src="https://i.postimg.cc/8zjv8vFC/pic-4.png" alt="">
                    <div class="info">
                        <h3>Alice</h3>
                        <span>designer</span>
                    </div>
                    <i class="fas fa-quote-left"></i>
                </div>
            </div>
        </div>
    </div>

</section>


<section id="blogs" class="blogs">
    <h1 class="heading"> <span>Nossos Serviços</span> </h1>
<div class="container">
    <div class="services">

    <div class="service-card">
    <img src="img/musculacao.jpeg" alt="Musculação" class="service-image" />
    <div class="service-content">
        <h3 class="service-title">Musculação</h3>
        <p class="service-description">
        Treinamento com pesos para ganho de força e massa muscular, com orientação profissional.
        </p>
    </div>
    </div>

    <div class="service-card">
    <img src="img/crossfit.jpg" alt="Crossfit" class="service-image" />
    <div class="service-content">
        <h3 class="service-title">Crossfit</h3>
        <p class="service-description">
        Exercícios de alta intensidade para melhorar desempenho físico, força e agilidade.
        </p>
    </div>
    </div>

    <div class="service-card">
    <img src="img/funcional2.jpg" alt="Funcional" class="service-image" />
    <div class="service-content">
        <h3 class="service-title">Funcional</h3>
        <p class="service-description">
        Atividades com peso corporal, equilíbrio e coordenação para saúde e mobilidade.
        </p>
    </div>
    </div>

    <div class="service-card">
    <img src="img/pilates.jpg" alt="Pilates" class="service-image" />
    <div class="service-content">
        <h3 class="service-title">Pilates</h3>
        <p class="service-description">
        Aulas para fortalecer o core, melhorar postura e reduzir dores nas articulações.
        </p>
    </div>
    </div>

    <div class="service-card">
    <img src="img/personaltreino.png" alt="Personal Trainer" class="service-image" />
    <div class="service-content">
        <h3 class="service-title">Personalizado</h3>
        <p class="service-description">
        Atendimento 1:1 com plano de treino adaptado aos seus objetivos e condição física.
        </p>
    </div>
    </div>

    <div class="service-card">
    <img src="img/nutricionista.jpg" alt="Nutricionista" class="service-image" />
    <div class="service-content">
        <h3 class="service-title">Nutricionista</h3>
        <p class="service-description">
        Acompanhamento profissional da sua dieta para melhorar seu desempenho e resultados.
        </p>
    </div>
    </div>

    </div>
    </div>
</div>
</section>
  
</main>


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

<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script src='main.js'></script>
</body>


</html>