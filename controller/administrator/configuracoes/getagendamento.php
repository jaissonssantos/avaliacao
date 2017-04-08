<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
$response = new stdClass();

//get session local browser
$idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];

if (!isset($idestabelecimento)) {
    throw new Exception('Ops! faça o login novamente para executar a operação', 400);
}

try {
    $stmt = $oConexao->prepare(
        'SELECT
			a.campomsgpgtoobrigatorio as messagerequired, a.campomsgpgtodescricao as message
		FROM estabelecimento_configuracao a
		WHERE a.idestabelecimento = :idestabelecimento 
		LIMIT 0,1'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $agendamento = $stmt->fetchObject();

    if (!$agendamento) {
        throw new Exception('Não encontrado', 404);
    } else {
        $agendamento->messagerequired = ($agendamento->messagerequired == '1') ? true : false;
    }

    http_response_code(200);
    $response = $agendamento;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
