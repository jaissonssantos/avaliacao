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
				DATE_FORMAT(ag.horainicial, "%d/%m/%Y") as data, ag.observacao, ag.status, 
				pf.id as idprofissional, pf.nome as profissional, pf.profissao,
				cl.nome as clientenome, cl.telefonecelular as clientenumerocelular, cl.email as clienteemail,
				cv.id as idconvenio, cv.nome as convenio,
				et.cnpjcpf as estabelecimentocpnj, et.nomefantasia as estabelecimentonome, et.email as estabelecimentoemail,
				et.telefonecomercial as estabelecimentotelefone, et.cep, et.logradouro, et.numero, et.complemento, et.bairro,
				c.nome as cidade, c.sigla as estado
			FROM agendamento ag 
			LEFT JOIN convenio cv ON(ag.idconvenio = cv.id)
			LEFT JOIN estabelecimento et ON(ag.idestabelecimento = et.id)
			LEFT JOIN cidade c ON(et.idcidade = c.idcidade)
			LEFT JOIN profissional pf ON(ag.idprofissional = pf.id)
			LEFT JOIN cliente cl ON(ag.idcliente = cl.id)
			WHERE ag.id=:id AND ag.idestabelecimento=:idestabelecimento AND ag.status=4
		LIMIT 1'
    );
    $stmt->execute(array('id' => $params->id, 'idestabelecimento' => $_SESSION['ang_plataforma_estabelecimento']));
    $comprovante = $stmt->fetchObject();

    if (!$comprovante) {
        throw new Exception('Não encontrado', 404);
    }

    $stmt = $oConexao->prepare(
        'SELECT id,codigo,valor,valorconvenio,DATE_FORMAT(datacadastro, "%d/%m/%Y") as data
			FROM agendamento_pagamento
			WHERE idagendamento=:id'
    );
    $stmt->execute(array('id' => $params->id));
    $comprovante->pagamento = $stmt->fetchObject();

    $stmt = $oConexao->prepare(
        'SELECT sv.id,sv.nome,api.valor
			FROM servico sv
			LEFT JOIN agendamento_pagamento_item api ON(sv.id = api.idservico)
			WHERE api.idagendamento_pagamento=:id
		ORDER BY sv.nome'
    );
    $stmt->execute(array('id' => $comprovante->pagamento->id));
    $comprovante->servicos = $stmt->fetchAll();

    http_response_code(200);
    $response = $comprovante;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
