<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $profissional = (array) $params;

    $required = array('nome', 'profissao', 'email', 'tempoconsulta', 'id');

    $profissional = array_intersect_key($profissional, array_flip($required));

    if (count($profissional) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'UPDATE profissional
			SET nome=:nome,profissao=:profissao,email=:email,
					tempoconsulta=:tempoconsulta
			WHERE id=:id'
        );
    $stmt->execute($profissional);

    if (!isset($params->servicos)) {
        throw new Exception('Selecione os serviços', 400);
    }

    if (!isset($params->diastrabalho)) {
        throw new Exception('Selecione os dias de atendimento', 400);
    }

    // Apaga todos os servicos do profissional
    $profissional_servico =
    $profissional_diastrabalho = array('idprofissional' => $profissional['id']);

    $stmt = $oConexao->prepare(
    'DELETE FROM profissional_servico
	 	WHERE idprofissional = :idprofissional
	');
    $stmt->execute($profissional_servico);

    // Apaga todos os dias de atendimento do profissional
    $stmt = $oConexao->prepare(
    'DELETE FROM profissional_diastrabalho
	 	WHERE idprofissional = :idprofissional
	');
    $stmt->execute($profissional_diastrabalho);

    $stmt = $oConexao->prepare(
    'INSERT INTO profissional_servico(
			idprofissional,idservico
		) VALUES (
			:idprofissional,:idservico
		)');

    $servicos = $params->servicos;
    if (is_array($servicos)) {
        foreach ($servicos as $servico) {
            if (isset($servico->id)) {
                $profissional_servico['idservico'] = $servico->id;
                $stmt->execute($profissional_servico);
            }
        }
    }

    $stmt = $oConexao->prepare(
    'INSERT INTO profissional_diastrabalho(
			idprofissional,dia,horainicial,horafinal
		) VALUES (
			:idprofissional,:dia,:horainicial,:horafinal
		)');

    $diastrabalho = $params->diastrabalho;
    if (is_array($diastrabalho)) {
        foreach ($diastrabalho as $diatrabalho) {
            if (isset($diatrabalho->dia, $diatrabalho->horainicial, $diatrabalho->horafinal)) {
                $profissional_diastrabalho['dia'] = $diatrabalho->dia;
                $profissional_diastrabalho['horainicial'] = substr_replace($diatrabalho->horainicial, ':', 2, 0).':00';
                $profissional_diastrabalho['horafinal'] = substr_replace($diatrabalho->horafinal, ':', 2, 0).':00';
                $stmt->execute($profissional_diastrabalho);
            }
        }
    }

    $oConexao->commit();
    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    echo $e->getMessage();
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
