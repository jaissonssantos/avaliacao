<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->id)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    if (!isset($params->servicos)) {
        throw new Exception('Selecione os serviços', 400);
    }

    $agenda = (array) $params;
    // Default Parameters
    $agenda['horainicial'] = formata_data($params->reagenda->data).' '.$params->reagenda->horario.':00';
    $agenda['horafinal'] = date('Y-m-d H:i:s', strtotime($agenda['horainicial']."+{$params->reagenda->duracao} minutes"));
    $agenda['idconvenio'] = isset($params->idconvenio) ?
                                    $params->idconvenio
                                    : null;

    $required = array(
                    'id',
                    'idconvenio',
                    'horainicial',
                    'horafinal',
                    'observacao',
                );
    $agendamento = (array) $agenda;
    $agendamento = array_intersect_key($agendamento, array_flip($required));

    $oConexao->beginTransaction();

    //insere o agendamento
    $stmt = $oConexao->prepare(
        'UPDATE agendamento SET 
			idconvenio=:idconvenio,horainicial=:horainicial,horafinal=:horafinal,observacao=:observacao 
		WHERE id=:id');
    $stmt->execute($agendamento);

    //apaga os servicos do agendamento
    $stmt = $oConexao->prepare(
        'DELETE FROM agendamento_servico 
			WHERE idagendamento=:id');
    $stmt->execute(array('id' => $params->id));

    //Inserir o servico do agendamento
    $stmt = $oConexao->prepare(
            'INSERT INTO 
    			agendamento_servico(
    				idservico,idagendamento
    			) VALUES (
    				:idservico,:idagendamento
    			)'
    );
    $agendamento_servico = array('idagendamento' => $params->id);
    $servicos = $params->servicos;
    foreach ($servicos as $servico) {
        if (isset($servico->id)) {
            $agendamento_servico['idservico'] = $servico->id;
            $stmt->execute($agendamento_servico);
        }
    }

    $oConexao->commit();
    http_response_code(200);
    $response->success = 'Pronto! Seu agendamento está remarcado';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
