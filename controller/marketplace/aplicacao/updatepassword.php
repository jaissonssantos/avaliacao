<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    if (!isset(
        $params->token,
        $params->password
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT idcliente,token 
        FROM cliente_token
        WHERE 
            token=?'
    );
    $stmt->execute(array(
        $params->token
    ));
    $results = $stmt->fetchObject();

    if (!$results) {
        throw new Exception('Token já foi utilizado, faça uma nova tentativa para recuperar sua senha', 404);
    }

    $stmt = $oConexao->prepare(
        'UPDATE cliente SET
        senha=?
        WHERE 
            id=?'
    );
    $stmt->execute(array(
        sha1(SALT.$params->password),
        $results->idcliente
    ));

    $stmt = $oConexao->prepare(
        'UPDATE cliente_token SET
        status=2
        WHERE 
            token=?'
    );
    $stmt->execute(array(
        $params->token
    ));

    http_response_code(200);
    $response->success = 'Sua senha foi atualizada, faça o login novamente';

} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
