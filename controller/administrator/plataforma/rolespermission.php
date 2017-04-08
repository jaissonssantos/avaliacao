<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
$response = new stdClass();

try {
    $uid = $_SESSION['ang_plataforma_uid'];

    $stmt = $oConexao->prepare('SELECT roles
									FROM usuario_permissao
								WHERE idusuario = :uid');
    $stmt->bindValue('uid', $uid);
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_OBJ);
    $oConexao = null;

    if ($roles) {
        http_response_code(200);
        $response = $roles;
    } else {
        $response->roles = 'null';
    }

    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}
