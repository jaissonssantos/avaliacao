<?php

use Utils\Conexao;

header('Content-type: application/json');
$response = new stdClass();

try {

    if (!isset(
        $_SESSION['avaliacao_cliente_uid'],
        $_SESSION['avaliacao_cliente_nome'],
        $_SESSION['avaliacao_cliente_email']
    )) {
        throw new Exception('Visitante não credenciado', 400);
    }

    $results = new stdClass();
    $results->id = $_SESSION['avaliacao_cliente_uid'];
    $results->nome = $_SESSION['avaliacao_cliente_nome'];
    $results->email = $_SESSION['avaliacao_cliente_email'];
                       
    http_response_code(200);
    $response = array(
        'results' => $results
    );

} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
