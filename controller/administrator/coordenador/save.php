<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

//get session local browser
//$uid = $_SESSION['ang_ktcomentario_uid'];
$response = new stdClass();

try {

    //inserir o comentario
    $stmt = $oConexao->prepare(
        'INSERT INTO coordenador(
			nome, celular1, celular2, email, status, celular3, celular4, idregional, idcidade
		)VALUES(
			:nome, :celular1, :celular2, :email, :status, :celular3, :celular4, :idregional, :idcidade
		)
	');
    $stmt->bindValue('nome', $params->nome);
    $stmt->bindValue('celular1', $params->celular1);
    $stmt->bindValue('celular2', $params->celular2);
    $stmt->bindValue('email', $params->email);
    $stmt->bindValue('status', $params->status);
    $stmt->bindValue('celular3', $params->celular3);
    $stmt->bindValue('celular4', $params->celular4);
    $stmt->bindValue('idregional', $params->idregional);
    $stmt->bindValue('idcidade', $params->idcidade);
    $stmt->execute();

    http_response_code(200);
    $response->success = 'Coordenador salvo com sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
