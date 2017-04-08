<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->token != '') {
        $stmt = $oConexao->prepare('SELECT token, data_envio, data_expiracao
      							FROM cliente_token
      						WHERE token = ? AND data_expiracao >= NOW()');
        $stmt->bindValue(1, $params->token);
        $stmt->execute();
        $resultToken = $stmt->fetchObject();

        if ($resultToken) {
            $msg = array();
            $msg['token'] = 'valid';
            echo json_encode($msg);
        } else {
            echo '{ "token": "invalid" }';
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "token": "invalid",  "error": "'.$e->getMessage().'" }';
    die();
}
