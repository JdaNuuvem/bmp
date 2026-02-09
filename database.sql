-- Create database manually if it doesn't exist (Postgres usually requires this outside of the connection)
-- In docker-entrypoint-initdb.d, the DB in POSTGRES_DB is created automatically.
-- We connect to that DB.

-- Tabela de Leads (Clientes)
CREATE TABLE IF NOT EXISTS leads (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    tempo_trabalho VARCHAR(50) NULL,
    cpf VARCHAR(14) NULL,
    rg VARCHAR(20) NULL,
    data_nascimento DATE NULL,
    cep VARCHAR(10) NULL,
    logradouro VARCHAR(255) NULL,
    numero VARCHAR(20) NULL,
    complemento VARCHAR(255),
    bairro VARCHAR(100) NULL,
    cidade VARCHAR(100) NULL,
    uf CHAR(2) NULL,
    app_fgts VARCHAR(3) NULL,
    atendido BOOLEAN DEFAULT FALSE,
    utm_source VARCHAR(100) DEFAULT NULL,
    utm_medium VARCHAR(100) DEFAULT NULL,
    utm_campaign VARCHAR(100) DEFAULT NULL,
    referrer VARCHAR(100) DEFAULT NULL,
    referrer_url VARCHAR(500) DEFAULT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Administradores
CREATE TABLE IF NOT EXISTS admins (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Inserir usuário admin padrão (Senha: Absurdo25@)
-- O hash abaixo é referente a senha 'Absurdo25@'
INSERT INTO admins (username, password) 
SELECT 'bmpadmin', '$2a$13$T31PcrgfsLriyrwvIN3KC.Zr2EmaEP.qykz6aYaZMIeOI7PX816gC'
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE username = 'bmpadmin');