<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

if (!isset($params->id)) {
    throw new Exception('Verifique os dados preenchidos', 400);
}

$param = array();
$param['id'] = $params->id;
$param['idestabelecimento'] = $_SESSION['ang_plataforma_estabelecimento'];

try {
    $stmt = $oConexao->prepare(
        'SELECT
			cv.id, cv.nome
		FROM convenio cv
		WHERE cv.id=:id AND cv.idestabelecimento=:idestabelecimento
		LIMIT 1'
    );

    $stmt->execute($param);
    $convenio = $stmt->fetchObject();

    if (!$convenio) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $convenio;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
