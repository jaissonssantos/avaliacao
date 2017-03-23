<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $servicos = isset($params->servicos) ? $params->servicos : null;
    $status = isset($params->status) ? $params->status : 1;

    if (!is_array($servicos)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $idsServicos = implode(',', $servicos);

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'UPDATE coordenador sv SET status=?'.
        'WHERE FIND_IN_SET(cast(sv.id AS CHAR), ?)'
    );
    $stmt->execute(array($status, $idsServicos));

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
    $oConexao->commit();
} catch (PDOException $e) {
    //echo $e->getMessage();
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

    echo json_encode($response);
