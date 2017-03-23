<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $profissionais = isset($params->profissionais) ? $params->profissionais : null;
    $status = isset($params->status) ? $params->status : 1;

    if (!is_array($profissionais)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $idsProfissionais = implode(',', $profissionais);

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'UPDATE profissional pf SET status=?'.
        'WHERE FIND_IN_SET(cast(pf.id AS CHAR), ?)'
    );
    $stmt->execute(array($status, $idsProfissionais));

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
