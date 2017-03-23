<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $convenios = isset($params->convenios) ? $params->convenios : null;
    $status = isset($params->status) ? $params->status : 1;

    if (!is_array($convenios)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $idsConvenios = implode(',', $convenios);

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'UPDATE convenio cv SET status=?'.
        'WHERE FIND_IN_SET(cast(cv.id AS CHAR), ?)'
    );
    $stmt->execute(array($status, $idsConvenios));

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
    $oConexao->commit();
} catch (PDOException $e) {
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
