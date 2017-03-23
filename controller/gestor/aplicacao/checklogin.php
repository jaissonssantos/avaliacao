<?php

use Utils\Conexao;

header('Content-type: application/json');
$response = new stdClass();

try {

    if (!isset(
        $_SESSION['ang_gestor_uid'],
        $_SESSION['ang_gestor_name'],
        $_SESSION['ang_gestor_login'],
        $_SESSION['ang_gestor_email'],
        $_SESSION['ang_gestor_roles']
    )) {
        throw new Exception('Visitante não credenciado', 400);
    }
    $results = new stdClass();
    $results->id = $_SESSION['ang_gestor_uid'];
    $results->name = $_SESSION['ang_gestor_name'];
    $results->login = $_SESSION['ang_gestor_login'];
    $results->email = $_SESSION['ang_gestor_email'];
    $results->roles = $_SESSION['ang_gestor_roles'];

    http_response_code(200);
    $response = array(
        'results' => $results
    );

} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
