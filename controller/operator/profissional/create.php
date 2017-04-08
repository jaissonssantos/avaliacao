<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $profissional = (array) $params;

    $required = array('nome', 'profissao', 'email', 'tempoconsulta');

    $profissional = array_intersect_key($profissional, array_flip($required));

    if (count($profissional) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Default Parameters
    $profissional['idestabelecimento'] = intval($_SESSION['ang_plataforma_estabelecimento']);
//    $profissional['liberado'] = 1;
    $profissional['status'] = 1;
    $profissional['idusuario'] = intval($_SESSION['ang_plataforma_uid']);

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
    'INSERT INTO
		profissional(
			nome,profissao,email,tempoconsulta,idestabelecimento,idusuario,
			status,datacadastro
		) VALUES (
			:nome,:profissao,:email,:tempoconsulta,:idestabelecimento,:idusuario,
			:status,now()
		)');

    $stmt->execute($profissional);
    $idprofissional = $oConexao->lastInsertId();

    if (!isset($params->servicos)) {
        throw new Exception('Selecione os serviços', 400);
    }

    if (!isset($params->diastrabalho)) {
        throw new Exception('Selecione os dias de atendimento', 400);
    }

    $stmt = $oConexao->prepare(
    'INSERT INTO profissional_servico(
			idprofissional,idservico
		) VALUES (
			:idprofissional,:idservico
		)');
    $profissional_servico = array('idprofissional' => $idprofissional);

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
    $profissional_diastrabalho = array('idprofissional' => $idprofissional);

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
    $response->success = 'Cadastrado com sucesso';
} catch (PDOException $e) {
//    echo $e->getMessage();
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
