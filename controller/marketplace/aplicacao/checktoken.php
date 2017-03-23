<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    if (!isset(
        $params->token
    )) {
        throw new Exception('Token inválido', 400);
    }
        
    $stmt = $oConexao->prepare(
        'SELECT token
        FROM cliente_token
        WHERE 
            token=?
        AND 
            dataativacao>=NOW()
        AND
            status=1'
    );
    $stmt->execute(array(
       $params->token 
    ));
    $results = $stmt->fetchObject();

    if (!$results) {
        throw new Exception('Token inválido ou já foi utilizado', 404);
    }
    http_response_code(200);
    $response->success = 'Token válido';

} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);

