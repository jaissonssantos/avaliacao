<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
// $params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    // $offset = isset($params->offset) && $params->offset > 0
    // 					? $params->offset
    // 					: 0;
    // $limit = isset($params->limit) && $params->limit < 200
    // 					? $params->limit
    // 					: 200;

    // $search = isset($params->search[0])
    // 					? " AND ct.nome LIKE :query "
    // 					: null;

    $stmt = $oConexao->prepare(
        'SELECT
			ct.id,ct.nome
		FROM servico_categoria ct'
    );
    // LIMIT :offset,:limit
    // $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    // $stmt->bindParam('limit', $limit, PDO::PARAM_INT);

    // if(isset($params->search[0])){
    // 	$query = '%'.$params->search.'%';
    // 	$stmt->bindParam('query', $query);
    // }

    $stmt->execute();
    $results = $stmt->fetchAll();

    http_response_code(200);
    $response->results = $results;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    echo $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
