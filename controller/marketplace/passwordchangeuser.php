<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->token != '') {
        $msg = array();

        $stmt = $oConexao->prepare('SELECT a.id as idcliente, b.token 
						                      FROM cliente a 
                                  INNER JOIN cliente_token b ON (a.id = b.idcliente)
							                     WHERE 
                                   b.token = :token');
        $stmt->bindValue('token', $params->token);
        $stmt->execute();
        $isToken = $stmt->fetchObject();

        if ($isToken) {

        /* update cliente password on token */
        $stmt = $oConexao->prepare('UPDATE cliente SET senha = :password WHERE id = :id');
            $stmt->bindValue('password', sha1(SALT.$params->password));
            $stmt->bindValue('id', $isToken->idcliente);
            $update = $stmt->execute();

      /* update cliente token of inactive */
      $stmt = $oConexao->prepare('UPDATE cliente_token SET liberado = 2 WHERE token = :token');
            $stmt->bindValue('token', $params->token);
            $update = $stmt->execute();

            if ($update) {
                /* close connection */
            $oConexao = null;

                $msg['status'] = 'success';
                $msg['message'] = 'Sua senha foi atualizada, faça o login novamente';
                echo json_encode($msg);
            } else {
                echo '{ "status": "error", "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
            }
        } else {
            echo '{ "status": "error", "message": "Token já foi utilizado, faça uma nova tentativa para recuperar sua senha" }';
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
