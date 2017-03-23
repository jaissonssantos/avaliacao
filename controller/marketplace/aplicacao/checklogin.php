<?php

use Utils\Conexao;

header('Content-type: application/json');
$response = new stdClass();

try {

    if (!isset(
        $_SESSION['ang_markday_uid'],
        $_SESSION['ang_markday_name']
    )) {
        throw new Exception('Visitante não credenciado', 400);
    }
    $results = new stdClass();
    $results->id = $_SESSION['ang_markday_uid'];
    $results->nome = $_SESSION['ang_markday_name'];
    $results->cpf = $_SESSION['ang_markday_cpf'];
    $results->email = $_SESSION['ang_markday_email'];
    $results->imagem = isset($_SESSION['ang_markday_thumbnail']) 
                       ? $_SESSION['ang_markday_thumbnail'] 
                       : STORAGE_URL . '/cliente/default.png';
                       
    http_response_code(200);
    $response = array(
        'results' => $results
    );

} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
