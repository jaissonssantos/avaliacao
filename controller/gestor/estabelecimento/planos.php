<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $stmt = $oConexao->prepare('SELECT id,nome,mensal,anual FROM plano');
    $stmt->execute();

    http_response_code(200);
    $results = $stmt->fetchAll();
    if (!$results) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }
    $response = $results;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
