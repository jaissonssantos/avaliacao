<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($_SESSION['ang_secc_uid'])) {
        throw new Exception('Sessão não definida', 400);
    }

    if (!isset($params->senha)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare('UPDATE usuario SET senha = ? WHERE email = :email');
    $stmt->bindParam('senha', sha1($params->senha));
    $stmt->bindParam('email', $_SESSION['ang_secc_email']);
    $usuario = $stmt->execute();

    if (!$usuario) {
        throw new Exception('Não foi possível redefinir a senha', 304);
    }

    http_response_code(200);
    $response->success = 'Senha redefinida com sucesso';
    $oConexao->rollBack();
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
