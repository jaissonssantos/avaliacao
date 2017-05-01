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

    $search = isset($params->search[0])
                        ? ' AND
                                (
                                    cl.nome LIKE :query OR
                                    cl.email LIKE :query OR
                                    cl.telefone LIKE :query
                                )
                            '
                        : null;

    $idestabelecimento = $_SESSION['avaliacao_estabelecimento'];

    $stmt = $oConexao->prepare(
        'SELECT
            cl.id,cl.nome,cl.email,cl.telefone
        FROM estabelecimento_cliente ec
        INNER JOIN cliente cl ON (ec.idcliente = cl.id)
        WHERE ec.idestabelecimento =:idestabelecimento'.$search.'
        ORDER BY cl.id DESC
        LIMIT :offset,:limit'
    );
    $stmt->bindParam('idestabelecimento', $idestabelecimento, PDO::PARAM_INT);
    $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam('limit', $limit, PDO::PARAM_INT);

    if (isset($params->search[0])) {
        $query = '%'.$params->search.'%';
        $stmt->bindParam('query', $query);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM estabelecimento_cliente ec
        INNER JOIN cliente cl ON (ec.idcliente = cl.id)
        WHERE ec.idestabelecimento =:idestabelecimento'.$search
    );

    if (isset($params->search[0])) {
        $query = '%'.$params->search.'%';
        $count->bindParam('query', $query);
    }

    $count->bindParam('idestabelecimento', $idestabelecimento, PDO::PARAM_INT);
    $count->execute();
    $count_results = $count->fetchColumn();

    http_response_code(200);
    $response = array(
        'results' => $results,
        'count' => array(
            'results' => $count_results
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
