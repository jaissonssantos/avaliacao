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
			pub.id,pub.idestabelecimento,pub.nome,pub.tipo,pub.ordenacao,
			DATE_FORMAT(pub.data_inicio, "%d/%m/%Y") as data_inicio,
			DATE_FORMAT(pub.data_fim, "%d/%m/%Y") as data_fim,
			pub.path_imagem,pub.url,pub.descricao,pub.valor
		FROM publicidade pub
		WHERE pub.id = ?
		LIMIT 1'
    );

    $stmt->execute(array($id));
    $publicidade = $stmt->fetchObject();

    if (!$publicidade) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $publicidade;
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

// Imprime o resultado final
echo json_encode($response);
