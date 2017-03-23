<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $params->idcliente = $_SESSION['ang_markday_uid'];

    if (!isset(
        $params->idcliente,
        $params->senhaatual,
        $params->novasenha
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT count(id) as total 
        FROM cliente
        WHERE
            id=?
        AND
            senha<>?'
    );

    $stmt->execute(array(
        $params->idcliente,
        sha1(SALT.$params->senhaatual)
    ));
    $results = $stmt->fetchObject();

    if(intval($results->total)>=1){
        throw new Exception('Achamos que você informou a senha atual errada, verifique', 400);
    }else{
        $oConexao->beginTransaction();
        $stmt = $oConexao->prepare('UPDATE cliente 
                    SET senha=?
                    WHERE
                        id=?');
        $stmt->execute(array(
            sha1(SALT.$params->novasenha),
            $params->idcliente
        ));

        http_response_code(200);
        $response->success = 'Senha atualizada com sucesso';
        $oConexao->commit();
    }

} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}


echo json_encode($response);
