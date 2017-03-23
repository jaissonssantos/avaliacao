<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->email)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $search = isset($params->id) && $params->id
                        ? ' AND us.id <> :id '
                        : null;

    $stmt = $oConexao->prepare('
			SELECT id, nome
			FROM usuario us
			WHERE upper(us.email) = upper(:email)' .$search.'
			LIMIT 1'
    );
    $stmt->bindParam('email', $params->email);
    if ($search) {
        $stmt->bindParam('id', $params->id);
    }
    $stmt->execute();
    $usuarioemail = $stmt->fetchObject();

    if (!$usuarioemail) {
        throw new Exception('Email não cadastrado', 404);
    }

    http_response_code(200);
    $response->success = 'Email encontrado';
} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
