<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $usuario = (array) $params;

    $required = array('id', 'senha');

    $usuario = array_intersect_key($usuario, array_flip($required));

    if (count($usuario) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $usuario['senha'] = sha1(SALT.$usuario['senha']);

    $stmt = $oConexao->prepare(
        'UPDATE usuario
			SET senha=:senha
			WHERE id=:id'
        );
    $stmt->execute($usuario);

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
