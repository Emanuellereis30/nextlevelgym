CREATE DATABASE nextlevel;
USE nextlevel;


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    plan VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reset_token_hash CHAR(64) DEFAULT NULL,
    reset_expires_at DATETIME DEFAULT NULL,
    tentativas INT DEFAULT 0,
    bloqueado TINYINT(1) DEFAULT 0,
    ultimo_erro DATETIME DEFAULT NULL,
    meta_treino VARCHAR(255) DEFAULT NULL
);


CREATE TABLE checkins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuarios_id INT NOT NULL,
    checkin_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan VARCHAR(50) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_pagamento DATE,
    status ENUM('PAGO', 'PENDENTE', 'ATRASADO') DEFAULT 'PENDENTE',
    metodo ENUM('cartao') DEFAULT 'cartao',
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


CREATE TABLE planos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) UNIQUE NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL
);

INSERT INTO planos (nome, descricao, preco) VALUES
('Básico', 'Acesso limitado', 99.90),
('Intermediário', 'Mais benefícios', 139.90),
('Premium', 'Acesso completo', 249.90);


CREATE TABLE professores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    especialidade VARCHAR(100) NOT NULL
);


CREATE TABLE aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    modalidade VARCHAR(100) NOT NULL,
    data_aula DATE NOT NULL,
    horario TIME NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    limite_vagas INT NOT NULL DEFAULT 20,
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE
);


CREATE TABLE alunos_aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aula_id INT NOT NULL,
    aluno_id INT NOT NULL,
    presente ENUM('S','N') DEFAULT 'N',
    UNIQUE aluno_unico_por_aula (aula_id, aluno_id),
    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE,
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    professor_id INT NOT NULL,
    modalidade VARCHAR(100) NOT NULL,
    data_aula DATE NOT NULL,
    horario TIME NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE
);
