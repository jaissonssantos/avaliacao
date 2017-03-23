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
        'UPDATE coordenador SET 
			nome=:nome, celular1 = :celular1, celular2 = :celular2, 
			celular3 = :celular3, 
			celular4 = :celular4, email = :email, status = :status, idregional = :idregional, 
			idcidade = :idcidade
			WHERE
			id=:id
	');
    $stmt->bindValue('nome', $params->nome);
    $stmt->bindValue('celular1', $params->celular1);
    $stmt->bindValue('celular2', $params->celular2);
    $stmt->bindValue('celular3', $params->celular3);
    $stmt->bindValue('celular4', $params->celular4);
    $stmt->bindValue('email', $params->email);
    $stmt->bindValue('status', $params->status);
    $stmt->bindValue('idregional', $params->idregional);
    $stmt->bindValue('idcidade', $params->idcidade);
    $stmt->bindValue('id', $params->id);
    $stmt->execute();

    http_response_code(200);
    $response->success = 'Coordenador atualizada com sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
