<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();
$response->count = array('ativos' => 0, 'inativos' => 0, 'arquivados' => 0);

try {
    $offset = isset($params->offset) && $params->offset > 0 ? $params->offset : 0;
    $limit = isset($params->limit) && $params->limit < 200 ? $params->limit : 200;
    $status = isset($params->status) ? (int) $params->status : 1;
    $search = isset($params->search[0])
                        ? ' AND(es.nomefantasia LIKE :query OR pub.nome LIKE :query OR pub.tipo LIKE :query) '
                        : null;

    $stmt = $oConexao->prepare(
        'SELECT 
			pub.id,pub.nome,pub.tipo,es.nomefantasia estabelecimento,pub.descricao,
			DATE_FORMAT(pub.data_inicio, "%d/%m/%Y") as data_inicio,pub.ordenacao,
			DATE_FORMAT(pub.data_fim, "%d/%m/%Y") as data_fim,pub.valor
		FROM publicidade pub
		LEFT JOIN estabelecimento es ON(pub.idestabelecimento = es.id)
		WHERE pub.status = :status '.$search.'
		ORDER BY pub.ordenacao ASC
		LIMIT :offset,:limit'
    );
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
        'SELECT COUNT(*)
		FROM publicidade pub
		LEFT JOIN estabelecimento es ON(pub.idestabelecimento = es.id)
		WHERE pub.status = :status' .' '.$search
    );

    if (isset($params->search[0])) {
        $query = '%'.$params->search.'%';
        $count->bindParam('query', $query);
    }

    $count->bindParam('status', $status);
    $count->execute();
    $count_results = $count->fetchColumn();

    $status = 1;
    $count->bindParam('status', $status);
    $count->execute();
    $count_ativos = $count->fetchColumn();

    $status = 2;
    $count->bindParam('status', $status);
    $count->execute();
    $count_inativos = $count->fetchColumn();

    $status = 3;
    $count->bindParam('status', $status);
    $count->execute();
    $count_arquivados = $count->fetchColumn();

    http_response_code(200);
    if (!$count_results && !$count_ativos && !$count_inativos && !$count_arquivados) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }
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
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
