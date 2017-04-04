<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->email)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare('SELECT id FROM usuario WHERE upper(email) = upper(:email) LIMIT 1');
    $stmt->bindParam('email', $params->email);
    $stmt->execute();
    $usuario = $stmt->fetchObject();

    if (!$usuario) {
        throw new Exception('Email não cadastrado', 404);
    }

    http_response_code(200);
    $response->success = 'Email encontrado';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
