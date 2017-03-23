<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    if (!isset(
        $_SESSION['ang_markday_uid']
    )) {
        throw new Exception('Faça login ou crie sua conta para favorita este estabelecimento', 400);
    }
    if (!isset(
        $params->estabelecimento
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }
    $uid = isset($_SESSION['ang_markday_uid']) ? $_SESSION['ang_markday_uid'] : null;

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'SELECT count(id) as total
        FROM estabelecimento_favorito
        WHERE
            idcliente=?
        AND
            idestabelecimento=?'
    );

    $stmt->execute(array(
        $uid,
        $params->estabelecimento
    ));
    $results = $stmt->fetchObject();

    if($results->total>=1){
        $stmt = $oConexao->prepare(
            'DELETE FROM estabelecimento_favorito
            WHERE
                idcliente=?
            AND
                idestabelecimento=?'
        );

        $stmt->execute(array(
            $uid,
            $params->estabelecimento
        ));
        $response->success = 'Estabelecimento desfavoritado';
        $response->favorite = false;

    }else{
        $stmt = $oConexao->prepare(
            'INSERT INTO 
            estabelecimento_favorito(idcliente,idestabelecimento
            ) VALUES(?,?)'
        );

        $stmt->execute(array(
            $uid,
            $params->estabelecimento
        ));

        $response->success = 'Estabelecimento favoritado';
        $response->favorite = true;
    }

    http_response_code(200);
    $oConexao->commit();

} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);