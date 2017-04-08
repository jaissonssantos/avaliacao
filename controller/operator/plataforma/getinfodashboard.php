<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
$response = new stdClass();

//get session local browser
$idestabelecimento = isset($_SESSION['ang_plataforma_estabelecimento']) ? $_SESSION['ang_plataforma_estabelecimento'] : false;

try {

    if (!$idestabelecimento) {
        throw new Exception('Ops! faça o login novamente para executar a operação', 400);
    }

    //total de clientes ativos
    $stmt = $oConexao->prepare(
        'SELECT
			count(id) as total
		FROM cliente_estabelecimento
		WHERE idestabelecimento = :idestabelecimento
			AND
			status=1'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $painel->totalcustomers = $stmt->fetchColumn();

    //total de serviços cancelados
    $stmt = $oConexao->prepare(
        'SELECT
			count(id) as total
		FROM agendamento
		WHERE idestabelecimento = :idestabelecimento
			AND
			status=5'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $painel->servicecanceled = $stmt->fetchColumn();

    //total de serviços agendados até o momento
    $stmt = $oConexao->prepare(
        'SELECT
			count(id) as total
		FROM agendamento
		WHERE idestabelecimento = :idestabelecimento
			AND status <= 3'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $painel->servicescheduling = $stmt->fetchColumn();

    //total de serviços agendados via marketplace
    $stmt = $oConexao->prepare(
        'SELECT
			count(id) as total
		FROM agendamento
		WHERE idestabelecimento = :idestabelecimento
			AND
				status=2'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $painel->marketplacescheduling = $stmt->fetchColumn();

    //total de serviços executados
    $stmt = $oConexao->prepare(
        'SELECT
			count(id) as total
		FROM agendamento
		WHERE idestabelecimento = :idestabelecimento
			AND
				status=4'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $painel->servicerun = $stmt->fetchColumn();

    //serviços mais executados
    $stmt = $oConexao->prepare(
        'SELECT
			c.nome, count(*) as total
		FROM agendamento a
		LEFT JOIN agendamento_servico b ON(a.id = b.idagendamento)
		LEFT JOIN servico c ON(b.idservico = c.id)
		WHERE a.idestabelecimento = :idestabelecimento
			AND
				a.status=4
			GROUP BY c.nome ORDER BY total DESC LIMIT 0,50'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $painel->servicerunmore = $stmt->fetchAll();
    if (empty($painel->servicerunmore)) {
        $painel->servicerunmore = null;
    }

    //aniversariantes do mês
    $stmt = $oConexao->prepare(
        'SELECT
			a.nome, a.imagem as image, a.telefonecelular as phone, DATE_FORMAT(a.datanascimento, "%d") as date
		FROM cliente a
		LEFT JOIN cliente_estabelecimento b ON(a.id = b.idcliente)
		WHERE idestabelecimento = :idestabelecimento
			AND
				MONTH(a.datanascimento) = MONTH(NOW())
		ORDER BY a.datanascimento ASC'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $painel->birthdaycostumers = $stmt->fetchAll();
    if (empty($painel->birthdaycostumers)) {
        $painel->birthdaycostumers = null;
    }

    if (!$painel) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $painel;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
