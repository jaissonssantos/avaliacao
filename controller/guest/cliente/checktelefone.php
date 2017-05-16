<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->telefone)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare('SELECT id FROM cliente WHERE telefone = :telefone LIMIT 1');
    $stmt->bindParam('telefone', $params->telefone);
    $stmt->execute();
    $results = $stmt->fetchObject();

    if (!$results) {
        throw new Exception('Telefone não cadastrado', 404);
    }

    http_response_code(200);
    $response->success = 'Telefone encontrado';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
