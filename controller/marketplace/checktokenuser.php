<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->token != '') {
        $stmt = $oConexao->prepare('SELECT token, dataenvio, dataativacao
      									FROM cliente_token
      										WHERE token = :token 
      										AND 
      										dataativacao >= NOW()
      										AND
      										liberado = 1');
        $stmt->bindValue('token', $params->token);
        $stmt->execute();
        $isToken = $stmt->fetchObject();

        if ($isToken) {
            $msg['status'] = 'success';
            $msg['token'] = 'valid';
            $msg['message'] = 'Token válido';
            echo json_encode($msg);
        } else {
            echo '{ "token": "invalid", "status": "error", "message": "O link de redefinição de sua senha não é válido ou já foi usado" }';
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
