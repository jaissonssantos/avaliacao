<?php


use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->nome)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $status = isset($params->status) ?
                        (int) $params->status
                        : 1;

    $stmt = $oConexao->prepare(
        'SELECT
			DISTINCT(c.nome), c.id, c.email, c.telefonecelular, c.cpf 
		FROM cliente c 
		WHERE c.nome LIKE :query
			AND c.status=:status
		LIMIT 0,10'
    );
    if (isset($params->nome)) {
        $query = '%'.$params->nome.'%';
        $stmt->bindParam('query', $query);
    }
    $stmt->bindParam('status', $status);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = $oConexao->prepare(
        'SELECT
			COUNT(*)
		FROM cliente c 
		WHERE c.nome LIKE :query
			AND c.status=:status'
    );
    if (isset($params->nome)) {
        $query = '%'.$params->nome.'%';
        $count->bindParam('query', $query);
    }

    $count->bindParam('status', $status);

    $count->execute();
    $count_results = $count->fetchColumn();

    if (!$results && !$count_results) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }

    http_response_code(200);
    $response = array(
        'results' => $results,
        'count' => array(
            'results' => $count_results,
        ),
    );
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
