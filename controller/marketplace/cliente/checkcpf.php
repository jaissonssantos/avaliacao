<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->cpf)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare('SELECT id,cpf FROM cliente WHERE upper(cpf) = upper(:cpf) LIMIT 1');
    $stmt->bindParam('cpf', $params->cpf);
    $stmt->execute();
    $cliente = $stmt->fetchObject();

    if (!$cliente) {
        throw new Exception('CPF não cadastrados', 404);
    }

    http_response_code(200);
    $response->success = 'CPF encontrado';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
