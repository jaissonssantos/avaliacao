<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $convenio = (array) $params;

    $required = array(
        'id',
        'nome',
    );

    $convenio = array_intersect_key($convenio, array_flip($required));

    if (count($convenio) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
            'UPDATE convenio
				SET nome=:nome
			WHERE id=:id'
    );
    $stmt->execute($convenio);

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
