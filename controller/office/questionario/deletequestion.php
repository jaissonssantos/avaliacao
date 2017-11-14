<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    if (!isset(
        $params->id
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    } 

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'SELECT id FROM pergunta WHERE id=?'
    );
    $stmt->execute(array(
        $params->id
    ));
    $results->pergunta = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($results->pergunta as $rows){
        $stmt = $oConexao->prepare(
            'SELECT id FROM resposta WHERE idpergunta=?'
        );
        $stmt->execute(array(
            $rows['id']
        ));
        $results->resposta = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($results->resposta as $row){
            $stmt = $oConexao->prepare(
            'DELETE FROM resposta_cliente
                WHERE idresposta=?
            ');
            $stmt->execute(array(
                $row['id']
            ));
        }

        $stmt = $oConexao->prepare(
        'DELETE FROM resposta
            WHERE idpergunta=?
        ');
        $stmt->execute(array(
            $rows['id']
        ));
    }

    $stmt = $oConexao->prepare(
        'DELETE FROM pergunta
            WHERE id=?
        ');
        $stmt->execute(array(
            $params->id
        ));

    $oConexao->commit();

    http_response_code(200);
    $response->success = 'Deletado com sucesso';
} catch (PDOException $e) {
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde: '. $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
