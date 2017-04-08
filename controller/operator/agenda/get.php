<?php


use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->id)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT 
				ag.id,
				DATE_FORMAT(ag.horainicial, "%d/%m/%Y") as data, 
				DATE_FORMAT(ag.horainicial, "%H:%i") as horainicial, 
				DATE_FORMAT(ag.horafinal, "%H:%i") as horafinal, ag.observacao, ag.status, 
				pf.id as idprofissional, pf.nome as profissional, pf.profissao, pf.tempoconsulta as duracao, 
				cl.nome as clientenome, cl.telefonecelular, cl.email as clienteemail,
				cv.id as idconvenio, cv.nome as convenio
			FROM agendamento ag 
			LEFT JOIN convenio cv ON(ag.idconvenio = cv.id)
			LEFT JOIN estabelecimento et ON(ag.idestabelecimento = et.id)
			LEFT JOIN profissional pf ON(ag.idprofissional = pf.id)
			LEFT JOIN cliente cl ON(ag.idcliente = cl.id)
			WHERE ag.id=:id AND ag.idestabelecimento=:idestabelecimento
		LIMIT 1'
    );
    $stmt->execute(array('id' => $params->id, 'idestabelecimento' => $_SESSION['ang_plataforma_estabelecimento']));
    $agenda = $stmt->fetchObject();

    if (!$agenda) {
        throw new Exception('Não encontrado', 404);
    }

    $stmt = $oConexao->prepare(
        'SELECT sv.id,sv.nome,sv.interno,sv.valorpororcamento,sv.valor,sv.valorpromocao,sv.promocao
			FROM servico sv
			LEFT JOIN agendamento_servico asv ON(sv.id = asv.idservico)
			WHERE asv.idagendamento=:id
		ORDER BY sv.nome'
    );
    $stmt->execute(array('id' => $params->id));
    $agenda->servicos = $stmt->fetchAll();

    http_response_code(200);
    $response = $agenda;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
