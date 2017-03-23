<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->query)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $allowedSearch = array('nome', 'login', 'email');
    $field = isset($params->field) && in_array($params->field, $allowedSearch)
                   ? $params->field
                   : $allowedSearch[0];
    $offset = isset($params->offset) && $params->offset > 0
                        ? $params->offset
                        : 0;
    $limit = isset($params->limit) && $params->limit < 200
                        ? $params->limit
                        : 200;

    $stmt = $oConexao->prepare(
        'SELECT
			u.nome,u.login,u.email,u.liberado,u.perfil,u.idestabelecimento,u.master,u.datacadastro,
			e.nomefantasia,e.email
		 FROM usuario u
		 INNER JOIN estabelecimento e ON (u.idestabelecimento = e.id)
		 WHERE '.$field.' LIKE :query
		 LIMIT :offset,:limit'
    );
    $stmt->bindParam('query', $params->query);
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
