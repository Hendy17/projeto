# Aplicação Web de Consulta de Endereço

Esta aplicação web permite buscar endereços utilizando o serviço da API ViaCEP. A aplicação foi construída utilizando PHP 5.6 ou superior, Bootstrap para a interface do usuário e JavaScript puro para interações.


## Pré-requisitos

- PHP 5.6 ou superior
- Servidor web (Apache, Nginx, etc.)
- Banco de dados MySQL

## Configuração do Projeto

### 1. Configurar o Banco de Dados

1. Crie um banco de dados MySQL.
2. Execute o script `sql/create_table.sql` para criar a tabela necessária.

```sql
CREATE TABLE enderecos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    cep VARCHAR(10) NOT NULL,
    logradouro VARCHAR(255),
    bairro VARCHAR(255),
    cidade VARCHAR(255),
    uf VARCHAR(2),
    UNIQUE (cep)
);
```

2.Configurar o Arquivo de Conexão com o Banco de Dados
No arquivo src/db.php, configure as credenciais do banco de dados:
```
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meubanco";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
```
3.Configurar o Servidor Web
Certifique-se de que seu servidor web esteja configurado para servir os arquivos da pasta public como raiz do seu site.

4.Executar a Aplicação
Abra o navegador e acesse a URL onde seu servidor web está rodando. Por exemplo:
```
http://localhost/projeto/index.php

```
## Funcionamento
1.O usuário digita o CEP no formulário e clica no botão "Buscar".

2.A aplicação verifica se o CEP já está salvo no banco de dados:
Se estiver, os dados são exibidos imediatamente.

3.Se não estiver, a aplicação faz uma consulta à API ViaCEP, salva os dados no banco e exibe os resultados.

4.A aplicação trata erros e exibe mensagens amigáveis ao usuário.

## Dependências
Bootstrap 5.3.3

API ViaCEP

## Adicional além do teste 

Validação do Campo de Entrada de CEP

Para garantir que o campo de entrada só aceite números enquanto o usuário digita, foi adicionado um evento `input` ao campo de entrada. Este evento filtra qualquer caractere que não seja um número, garantindo que apenas números sejam permitidos.




