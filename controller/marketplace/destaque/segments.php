<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $stmt = $oConexao->prepare(
        'SELECT DISTINCT seg.nome,seg.hash,seg.imagem
		FROM publicidade pub
		LEFT JOIN estabelecimento es ON(pub.idestabelecimento = es.id)
		LEFT JOIN segmento seg ON(es.idsegmento = seg.id)
		WHERE pub.status = 1
         AND NOW() BETWEEN data_inicio AND data_fim
		ORDER BY pub.ordenacao ASC'
    );

    $stmt->execute();
    $results = $stmt->fetchAll();

    http_response_code(200);
    if (!$results) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }

    foreach($results as &$segmento) {
        $segmento['imagem'] = STORAGE_URL . '/segmento/' . $segmento['imagem'];
    }

    $response = array(
        'results' => $results
    );
} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
