<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->login)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $search = isset($params->id) && $params->id
                        ? ' AND us.id <> :id '
                        : null;

    $stmt = $oConexao->prepare('
			SELECT id, nome
			FROM usuario us
			WHERE upper(us.login) = upper(:login)' .$search.'
			LIMIT 1'
    );
    $stmt->bindParam('login', $params->login);
    if ($search) {
        $stmt->bindParam('id', $params->id);
    }
    $stmt->execute();
    $usuariologin = $stmt->fetchObject();

    if (!$usuariologin) {
        throw new Exception('Login não cadastrado', 404);
    }

    http_response_code(200);
    $response->success = 'Login encontrado';
} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
