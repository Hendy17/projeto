<?php
include 'db.php';

header("Content-Type: application/xml; charset=UTF-8");

function array_to_xml($data, &$xml_data) {
    foreach($data as $key => $value) {
        if(is_array($value)) {
            if(is_numeric($key)){
                $key = 'item'.$key;
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}

$response = array();

if (isset($_GET['cep'])) {
    $cep = $_GET['cep'];

    // Verifica se o CEP já está no banco de dados
    $stmt = $mysqli->prepare("SELECT * FROM enderecos WHERE cep = ?");
    $stmt->bind_param("s", $cep);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Retorna os dados do banco de dados
        $row = $result->fetch_assoc();
        $response = array(
            'status' => 'success',
            'message' => 'CEP já consultado anteriormente.',
            'data' => $row
        );
    } else {
        // Faz a consulta na API ViaCEP
        $url = "https://viacep.com.br/ws/{$cep}/xml/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $api_response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $response = array(
                'status' => 'error',
                'message' => 'Erro ao consultar a API: ' . curl_error($ch)
            );
        } else {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code != 200) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Erro ao consultar a API: HTTP ' . $http_code
                );
            } else {
                if ($api_response) {
                    // Carrega o XML e extrai os dados
                    $xml = simplexml_load_string($api_response);
                    if (isset($xml->erro)) {
                        $response = array(
                            'status' => 'error',
                            'message' => 'CEP não encontrado.'
                        );
                    } else {
                        $logradouro = $xml->logradouro;
                        $bairro = $xml->bairro;
                        $cidade = $xml->localidade;
                        $uf = $xml->uf;

                        // Salva os dados no banco de dados
                        $stmt = $mysqli->prepare("INSERT INTO enderecos (cep, logradouro, bairro, cidade, uf) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssss", $cep, $logradouro, $bairro, $cidade, $uf);
                        $stmt->execute();

                        $response = array(
                            'status' => 'success',
                            'message' => 'Endereço encontrado.',
                            'data' => array(
                                'cep' => $cep,
                                'logradouro' => $logradouro,
                                'bairro' => $bairro,
                                'cidade' => $cidade,
                                'uf' => $uf
                            )
                        );
                    }
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Erro ao consultar o CEP. Tente novamente mais tarde.'
                    );
                }
            }
        }
        curl_close($ch);
    }
    $stmt->close();
} else {
    $response = array(
        'status' => 'error',
        'message' => 'CEP não informado.'
    );
}

$mysqli->close();

$xml_data = new SimpleXMLElement('<?xml version="1.0"?><response></response>');
array_to_xml($response, $xml_data);
print $xml_data->asXML();
?>
