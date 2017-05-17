<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

if (!isset($_SESSION['avaliacao_uid'])) {
    throw new Exception('Usuário não autenticado', 400);
}

$id = $_SESSION['avaliacao_uid'];

try {
    $stmt = $oConexao->prepare(
        'SELECT
			id,nome,sobrenome,email,
            DATE_FORMAT(created_at,"%d/%m/%Y %H:%i") created_at
		FROM usuario
		WHERE id = ?
		LIMIT 1'
    );

    $stmt->execute(array($id));
    $results = $stmt->fetchObject();

    if (!$results) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $results;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
