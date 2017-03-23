<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

//get session local browser
$idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];
$response = new stdClass();

try {
    $stmt = $oConexao->prepare(
        'SELECT count(id) as total 
			FROM estabelecimento_lembrete
		WHERE idestabelecimento = :idestabelecimento'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $lembrete = $stmt->fetchObject();
    $response->total = $lembrete->total;
    if ($lembrete->total) {
        $stmt = $oConexao->prepare(
                'UPDATE estabelecimento_lembrete
					SET descricao=:description
				WHERE idestabelecimento=:idestabelecimento'
        );
        $stmt->bindValue(':description', $params->description);
        $stmt->bindValue(':idestabelecimento', $idestabelecimento);
        $stmt->execute();

        http_response_code(200);
        $response->success = 'Atualizado com sucesso';
    } else {
        $stmt = $oConexao->prepare(
                'INSERT INTO estabelecimento_lembrete (idestabelecimento, descricao)
					VALUES(:idestabelecimento, :description)'
        );
        $stmt->bindValue(':description', $params->description);
        $stmt->bindValue(':idestabelecimento', $idestabelecimento);
        $stmt->execute();

        http_response_code(200);
        $response->success = 'Cadastrado com sucesso';
    }
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
