<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aplicação</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="bootstrap.css">
  
  <style>
      
  </style> 
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-body">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="#">Buscas por CEP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active text-white" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Conteúdos</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h1>Buscar Endereço</h1>
  <form id="cepForm" class="d-flex">
    <div class="mb-3 col-md-6">
      <label for="cep" class="form-label">CEP</label>
      <input type="text" class="form-control" id="cep" name="cep" placeholder="Digite o CEP" maxlength="8">
    </div>
    <button type="submit" class="btn btn-primary align-self-end ms-2">Buscar</button>
  </form>
  <div id="result" class="mt-4"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
<script>
  // Evento para impedir a inserção de caracteres não numéricos
  document.getElementById('cep').addEventListener('input', function(event) {
    var cepInput = event.target;
    cepInput.value = cepInput.value.replace(/\D/g, ''); // Remove caracteres não numéricos
  });

  // Evento para tratar o envio do formulário
  document.getElementById('cepForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário
    var cep = document.getElementById('cep').value; // Obtém o valor do CEP inserido
    var resultDiv = document.getElementById('result'); // Div para exibir os resultados

    // Verificar se o CEP é numérico e possui 8 caracteres
    if (!/^\d{8}$/.test(cep)) {
      resultDiv.innerHTML = '<div class="alert alert-danger">Por favor, digite um CEP válido com 8 números.</div>';
      return;
    }

    // Cria uma solicitação AJAX para consultar o CEP
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/projeto/consulta_cep.php?cep=' + cep, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        var xml = xhr.responseXML; // Obtém a resposta XML
        var status = xml.getElementsByTagName('status')[0].childNodes[0].nodeValue; // status da resposta
        var message = xml.getElementsByTagName('message')[0].childNodes[0].nodeValue; // mensagem da resposta
        
        // Verifica se a consulta foi bem-sucedida
        if (status === 'success') {
          var data = xml.getElementsByTagName('data')[0]; // Obtém os dados retornados
          var cep = data.getElementsByTagName('cep')[0].childNodes[0].nodeValue;
          var logradouro = data.getElementsByTagName('logradouro')[0].childNodes[0].nodeValue;
          var bairro = data.getElementsByTagName('bairro')[0].childNodes[0].nodeValue;
          var cidade = data.getElementsByTagName('cidade')[0].childNodes[0].nodeValue;
          var uf = data.getElementsByTagName('uf')[0].childNodes[0].nodeValue;

         
          // Exibe os resultados da consulta em uma estrutura hstack
            resultDiv.innerHTML = '<div class="alert alert-success">' + message + '</div>';
            resultDiv.innerHTML += '<div class="hstack gap-3">';
            resultDiv.innerHTML += '<div class="p-2"><strong>CEP:</strong> ' + cep + '</div>';
            resultDiv.innerHTML += '<div class="p-2"><strong>Logradouro:</strong> ' + logradouro + '</div>';
            resultDiv.innerHTML += '<div class="p-2"><strong>Bairro:</strong> ' + bairro + '</div>';
            resultDiv.innerHTML += '<div class="p-2"><strong>Cidade:</strong> ' + cidade + '</div>';
            resultDiv.innerHTML += '<div class="p-2"><strong>UF:</strong> ' + uf + '</div>';
            resultDiv.innerHTML += '</div>';

        } else {
          // Exibe mensagem de erro em caso de falha na consulta
          resultDiv.innerHTML = '<div class="alert alert-danger">' + message + '</div>';
        }
      } else {
        // Exibe mensagem de erro em caso de falha na solicitação
        resultDiv.innerHTML = '<div class="alert alert-danger">Erro ao consultar o CEP. Tente novamente mais tarde.</div>';
      }
    };
    xhr.send(); // Envia a solicitação AJAX
  });
</script>
</body>
</html>
