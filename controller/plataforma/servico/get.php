<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

if (!isset($params->id)) {
    throw new Exception('Verifique os dados preenchidos', 400);
}

$id = $params->id;

try {
    $stmt = $oConexao->prepare(
        'SELECT
			sv.id,sv.nome,sv.descricao,sv.interno,sv.valorpororcamento,sv.valor,sv.promocao,sv.valorpromocao,sv.idusuario,
			sv.idservico_categoria,sv.duracao
		FROM servico sv
		WHERE sv.id = ?
		LIMIT 1'
    );

    $stmt->execute(array($id));
    $servico = $stmt->fetchObject();
    $servico->valorpororcamento = $servico->valorpororcamento == 1 ? true : false;
    $servico->promocao = $servico->promocao == 1 ? true : false;
    $servico->interno = $servico->interno == 1 ? true : false;

    if (!$servico) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $servico;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
