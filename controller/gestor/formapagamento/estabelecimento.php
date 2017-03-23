<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $offset = isset($params->offset) && $params->offset > 0
                        ? $params->offset
                        : 0;
    $limit = isset($params->limit) && $params->limit < 200
                        ? $params->limit
                        : 200;

    $stmt = $oConexao->prepare(
        'SELECT
			e.id,e.hash,e.cnpjcpf,e.razaosocial,e.nomefantasia,e.sobre,e.email,e.telefonecomercial,e.cep,e.logradouro,e.numero,e.complemento,e.bairro,e.licencainicial,e.licencafinal,e.datacadastro,
			pl.nome,pl.valormensal,pl.valorsemestral,pl.valoranual,
			es.nome,ci.nome
		 FROM estabelecimento e
		 INNER JOIN plano pl ON (e.idplano = pl.id)
		 INNER JOIN estado es ON (e.idestado = es.idestado)
		 INNER JOIN cidade ci ON (e.idcidade = ci.idcidade)
		 LIMIT :offset,:limit'
    );
    $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam('limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    http_response_code(200);
    $results = $stmt->fetchAll();
    if (!$results) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }
    $response = $results;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
