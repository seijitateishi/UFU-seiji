-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS veiculos_db;
USE veiculos_db;

-- Criação da tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de veículos
CREATE TABLE IF NOT EXISTS veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    ano INT NOT NULL,
    quilometragem INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    descricao TEXT,
    cidade VARCHAR(50) NOT NULL,
    foto_principal VARCHAR(255),
    foto_1 VARCHAR(255),
    foto_2 VARCHAR(255),
    usuario_id INT NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Criação da tabela de interesses
CREATE TABLE IF NOT EXISTS interesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    mensagem TEXT NOT NULL,
    contato VARCHAR(100),
    data_interesse TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inserção de dados de exemplo (opcional)
-- Inserir um usuário de exemplo (senha: 123456)
INSERT INTO usuarios (nome, cpf, email, senha, telefone) VALUES
('João Silva', '123.456.789-00', 'joao@example.com', '$2y$10$6OxDFqzviMiN4ybRf1dK4.xUOKwxI9fDFO5I3u9pYcfIcZT94aLx.', '(11) 98765-4321');

-- Inserir veículos de exemplo
INSERT INTO veiculos (marca, modelo, ano, quilometragem, valor, descricao, cidade, foto_principal, usuario_id) VALUES
('Fiat', 'Uno', 2015, 75000, 30000.00, 'Carro em ótimo estado, único dono, todas as revisões na concessionária.', 'São Paulo', 'images/uno.png', 1),
('Chevrolet', 'Corsa', 2018, 45000, 40000.00, 'Completo, ar condicionado, direção hidráulica, vidros elétricos.', 'Rio de Janeiro', 'images/corsa.png', 1),
('Ford', 'Focus', 2020, 20000, 55000.00, 'Seminovo, completo, teto solar, multimídia, câmera de ré.', 'Belo Horizonte', 'images/focus.png', 1); 