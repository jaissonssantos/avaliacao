<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    //default params session
    $idestabelecimento = $_SESSION['avaliacao_estabelecimento'];

    if (!isset(
        $params->id,
        $idestabelecimento
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT
            id,hash,titulo,introducao,status,prazo as data,
            DATE_FORMAT(prazo,"%H:%i") hora
        FROM questionario
        WHERE id=?
        AND idestabelecimento=?
        LIMIT 1'
    );

    $stmt->execute(array(
        $params->id,
        $idestabelecimento
    ));
    $results = $stmt->fetchObject();

    $stmt = $oConexao->prepare(
        'SELECT id,titulo,tipo,obrigatoria
        FROM pergunta
        WHERE idquestionario=?'
    );
    $stmt->execute(array(
        $params->id
    ));
    $results->pergunta = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count_pergunta = 0;
    foreach($results->pergunta as $row){
        if($row['tipo'] != 1){
            $stmt = $oConexao->prepare(
                'SELECT id,titulo
                FROM resposta
                WHERE
                    idpergunta=?'
            );

            $stmt->execute(array(
                $row['id']
            ));

            $resposta = $stmt->fetchAll(PDO::FETCH_ASSOC);

        }else{
            $resposta = null;
        }

        //associar resposta a pergunta
        $results->pergunta[$count_pergunta]['resposta'] = $resposta; 
        $count_pergunta++;
    }

    if (!$results) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $results;
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
