-- Tabela de usuários (anunciantes)
CREATE TABLE anunciante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senhaHash VARCHAR(255) NOT NULL
);

-- Tabela de anúncios
CREATE TABLE anuncio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_anunciante INT NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    ano INT NOT NULL,
    cor VARCHAR(30) NOT NULL,
    quilometragem INT NOT NULL,
    descricao TEXT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    estado CHAR(2) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    dataHora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_anunciante) REFERENCES anunciante(id) ON DELETE CASCADE
);

-- Tabela de fotos dos veículos
CREATE TABLE foto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_anuncio INT NOT NULL,
    nome_arquivo VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_anuncio) REFERENCES anuncio(id) ON DELETE CASCADE
);

-- Tabela de mensagens de interesse nos veículos
CREATE TABLE interesse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_anuncio INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    mensagem TEXT NOT NULL,
    dataHora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_anuncio) REFERENCES anuncio(id) ON DELETE CASCADE
);
