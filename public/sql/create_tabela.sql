CREATE TABLE enderecos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    cep VARCHAR(10) NOT NULL,
    logradouro VARCHAR(255),
    bairro VARCHAR(255),
    cidade VARCHAR(255),
    uf VARCHAR(2),
    UNIQUE (cep)
);
