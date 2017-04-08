<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();
$response->count = array('ativos' => 0, 'inativos' => 0, 'arquivados' => 0);

try {
    $offset = isset($params->offset) && $params->offset > 0
                        ? $params->offset
                        : 0;
    $limit = isset($params->limit) && $params->limit < 200
                        ? $params->limit
                        : 200;

    $status = isset($params->status)
                        ? $params->status
                        : 1;

    $search = isset($params->search[0])
                        ? ' AND
								(
									us.nome LIKE :query OR
									us.email LIKE :query OR
									us.login LIKE :query
								)
							'
                        : null;

    $idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];

    $stmt = $oConexao->prepare(
        'SELECT
			us.id,us.nome,us.email,us.login,us.perfil
		FROM usuario us
		LEFT JOIN estabelecimento es ON(us.idestabelecimento = es.id)
		WHERE es.id = :idestabelecimento AND us.status = :status '.$search.'
		LIMIT :offset,:limit'
    );
    $stmt->bindParam('idestabelecimento', $idestabelecimento);
    $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam('limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam('status', $status, PDO::PARAM_INT);

    if (isset($params->search[0])) {
        $query = '%'.$params->search.'%';
        $stmt->bindParam('query', $query);
    }

    $stmt->execute();
    $results = $stmt->fetchAll();

    $count = $oConexao->prepare(
        'SELECT
			COUNT(*)
		FROM usuario us
		LEFT JOIN estabelecimento es ON(us.idestabelecimento = es.id)
		WHERE es.id = :idestabelecimento AND us.status = :status' .' '.$search
    );

    if (isset($params->search[0])) {
        $query = '%'.$params->search.'%';
        $count->bindParam('query', $query);
    }

    $count->bindParam('idestabelecimento', $idestabelecimento);
    $count->bindParam('status', $status);
    $count->execute();
    $count_results = $count->fetchColumn();

    $status = 1;
    $count->bindParam('idestabelecimento', $idestabelecimento);
    $count->bindParam('status', $status);
    $count->execute();
    $count_ativos = $count->fetchColumn();

    $status = 2;
    $count->bindParam('idestabelecimento', $idestabelecimento);
    $count->bindParam('status', $status);
    $count->execute();
    $count_inativos = $count->fetchColumn();

    $status = 3;
    $count->bindParam('idestabelecimento', $idestabelecimento);
    $count->bindParam('status', $status);
    $count->execute();
    $count_arquivados = $count->fetchColumn();

    http_response_code(200);
    $response = array(
        'results' => $results,
        'count' => array(
            'results' => $count_results,
            'ativos' => $count_ativos,
            'inativos' => $count_inativos,
            'arquivados' => $count_arquivados,
        ),
    );
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
