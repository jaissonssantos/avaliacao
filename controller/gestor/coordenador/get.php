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
			a.*, r.nome as regional, c.nome as cidade
		FROM coordenador a
		LEFT JOIN regional r
		ON a.idregional=r.id
		LEFT JOIN cidade c
		ON a.idcidade=c.id
		WHERE a.id = :id
		LIMIT 1'
    );
    $stmt->bindValue('id', $params->id);
    $stmt->execute();
    $servico = $stmt->fetchObject();
    //$servico->valorpororcamento = $servico->valorpororcamento == 1 ? true : false;
    //$servico->promocao = $servico->promocao == 1 ? true : false;

    if (!$servico) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $servico;
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
