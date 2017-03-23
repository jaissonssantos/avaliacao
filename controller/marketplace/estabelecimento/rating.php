<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

	$avaliacao = (array) $params->parametro;
	$required = array('idestabelecimento','comentario', 'avaliacao');

    $avaliacao = array_intersect_key($avaliacao, array_flip($required));

    if (count($avaliacao) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    //parametros
    $avaliacao['idcliente'] = $_SESSION['ang_markday_uid'];
    $avaliacao['status'] = 2;

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
	    'INSERT INTO
			estabelecimento_avaliacao(
				idcliente,idestabelecimento,comentario,avaliacao,data,status
			) VALUES (
				:idcliente,:idestabelecimento,:comentario,:avaliacao,now(),:status
			)');

	$stmt->execute($avaliacao);

	http_response_code(200);
    $response->success = 'Obrigado por sua avaliação, em breve será verificada';
    $oConexao->commit();

} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);