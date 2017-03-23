<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

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
									e.nomefantasia LIKE :query OR
									e.razaosocial LIKE :query OR
									e.email LIKE :query OR
                                    e.bairro LIKE :query
								)
							'
                        : null;

    $stmt = $oConexao->prepare(
        'SELECT
			e.id,e.hash,e.cnpjcpf,e.razaosocial,e.nomefantasia,e.sobre,e.email,e.telefonecomercial,
            e.cep,e.logradouro,e.numero,e.complemento,e.bairro,
            DATE_FORMAT(e.licencainicial, "%d/%m/%Y") licencainicial,
            DATE_FORMAT(e.licencafinal, "%d/%m/%Y") licencafinal,
            e.datacadastro,pl.nome as plano,pl.mensal,pl.anual,
            es.nome as estado,ci.nome as cidade
		 FROM estabelecimento e
		 LEFT JOIN plano pl ON (e.idplano = pl.id)
		 INNER JOIN estado es ON (e.idestado = es.idestado)
		 INNER JOIN cidade ci ON (e.idcidade = ci.idcidade)
         WHERE e.status = :status '.$search.'
		 LIMIT :offset,:limit'
    );
    $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam('limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam('status', $status);


    if (isset($params->search[0])) {
        $query = '%'.$params->search.'%';
        $count->bindParam('query', $query);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    if (!$results) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }

    $count = $oConexao->prepare(
        'SELECT
			COUNT(*)
		 FROM estabelecimento e
         WHERE e.status = :status '.$search.'
		 '
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
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
