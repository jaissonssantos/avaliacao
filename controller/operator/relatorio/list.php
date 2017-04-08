<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];
    $offset = isset($params->offset) && $params->offset > 0 ? $params->offset : 0;
    $limit = isset($params->limit) && $params->limit < 200 ? $params->limit : 10000;

    $where = array();
    if (!empty($params->status)) {
        array_push($where, 'ag.status = :status');
    }
    if (!empty($params->profissional)) {
        array_push($where, 'ag.idprofissional = :profissional');
    }
    if (!empty($params->cliente)) {
        array_push($where, 'ag.idcliente = :cliente');
    }
    if (!empty($params->servico)) {
        array_push($where, 'EXISTS(select idservico from agendamento_servico ags where idservico = :servico and ags.idagendamento = ag.id limit 1)');
    }
    if (!empty($params->inicio)) {
        array_push($where, 'ag.horainicial > :inicio');
    }
    if (!empty($params->fim)) {
        array_push($where, 'ag.horafinal < :fim');
    }
    $where = count($where) ? implode(' AND ', $where) : '1';

    $stmt = $oConexao->prepare(
        'SELECT
			SQL_CALC_FOUND_ROWS ag.id,
			ag.id,
			cl.nome as cliente,
			pf.nome as profissional,
			ag.horainicial as data,
			ag.horafinal as datafim,
			ag.status as status,
			(select group_concat(
				concat(" ", sv.nome )
				) as servicos
				from agendamento_servico ags
				inner join servico sv on sv.id = ags.idservico
				where ags.idagendamento = ag.id
			) as servicos
		FROM agendamento ag
		INNER JOIN profissional pf ON (ag.idprofissional = pf.id)
		INNER JOIN cliente cl ON (ag.idcliente = cl.id)
		LEFT JOIN estabelecimento et ON(et.id = ag.idestabelecimento)
		WHERE  '.$where.'
			AND et.id=:idestabelecimento
		ORDER BY ag.horainicial ASC
		LIMIT :offset,:limit'
    );

    $stmt->bindParam('idestabelecimento', $idestabelecimento, PDO::PARAM_INT);
    $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam('limit', $limit, PDO::PARAM_INT);

    if (!empty($params->status)) {
        $stmt->bindParam('status', $params->status, PDO::PARAM_INT);
    }
    if (!empty($params->profissional)) {
        $stmt->bindParam('profissional', $params->profissional, PDO::PARAM_INT);
    }
    if (!empty($params->cliente)) {
        $stmt->bindParam('cliente', $params->cliente, PDO::PARAM_INT);
    }
    if (!empty($params->servico)) {
        $stmt->bindParam('servico', $params->servico, PDO::PARAM_INT);
    }
    if (!empty($params->inicio)) {
        $stmt->bindParam('inicio', date('Y-m-d', strtotime(str_replace('/', '-', $params->inicio))), PDO::PARAM_STR);
    }
    if (!empty($params->fim)) {
        $stmt->bindParam('fim', date('Y-m-d', strtotime(str_replace('/', '-', $params->fim))), PDO::PARAM_STR);
    }

    $stmt->execute();
    $results = $stmt->fetchAll();
    $count = $oConexao->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);

    http_response_code(200);
    $response = array(
        'results' => $results,
        'count' => $count,
    );
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    echo $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
