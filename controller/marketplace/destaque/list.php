<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $offset = isset($params->offset) && $params->offset > 0 ? $params->offset : 0;
    $limit = isset($params->limit) && $params->limit < 200 ? $params->limit : 200;
    $segment = isset($params->segment) ? $params->segment : false;

    $stmt = $oConexao->prepare(
        "SELECT 
			es.nomefantasia estabelecimento,pub.descricao,
			es.logradouro, es.numero, es.bairro, es.hash, es.imagem,
            seg.descricao as segmento_descricao,
            seg.nome as segmento_titulo,
			DATE_FORMAT(pub.data_inicio, '%d/%m/%Y') as data_inicio,pub.ordenacao,
			DATE_FORMAT(pub.data_fim, '%d/%m/%Y') as data_fim,pub.valor,seg.nome as segmento,
            DATE_FORMAT(hor.horainicial, '%H:%i') as abertura, 
            DATE_FORMAT(hor.horafinal, '%H:%i') as fechamento,
            (select min(sv.valor) from servico as sv where sv.idestabelecimento = es.id limit 1) as menor_preco,
            esp.id as pagina
		FROM publicidade pub
		LEFT JOIN estabelecimento es ON(pub.idestabelecimento = es.id)
		LEFT JOIN segmento seg ON(es.idsegmento = seg.id)
        LEFT JOIN estabelecimento_diasatendimento hor ON(hor.idestabelecimento = es.id)
        LEFT JOIN estabelecimento_page esp ON(es.id = esp.idestabelecimento)
		WHERE
			(NOW() BETWEEN data_inicio AND data_fim AND pub.status = 1)
            ".($segment ? 'AND seg.hash = :segment' : null)."
        GROUP BY es.id
		ORDER BY pub.ordenacao ASC
		LIMIT :offset,:limit"
    );
    //AND hor.dia = DAYOFWEEK(NOW()) - 2
    $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam('limit', $limit, PDO::PARAM_INT);
    if($segment) { 
        $stmt->bindParam('segment', $segment, PDO::PARAM_INT);
    }
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach($results as &$estabelecimento) {
        $estabelecimento->imagem = STORAGE_URL . '/estabelecimento/' . $estabelecimento->imagem;
    }

    http_response_code(200);
    if (!$results) {
        throw new Exception('Nenhum resultado encontrado', 404);
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