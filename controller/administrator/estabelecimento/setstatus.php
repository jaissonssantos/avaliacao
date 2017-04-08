<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $estabelecimentos = isset($params->estabelecimentos) ? $params->estabelecimentos : null;
    if (!is_array($estabelecimentos)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $status = isset($params->status) ? $params->status : 1;
    $idsServicos = implode(',', $estabelecimentos);
    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'UPDATE estabelecimento es SET status=?'.
        'WHERE FIND_IN_SET(cast(es.id AS CHAR), ?)'
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
