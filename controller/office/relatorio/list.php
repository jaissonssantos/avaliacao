<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();



/*if (!isset($params->hash)) {
    throw new Exception('Verifique os dados preenchidos', 400);
}*/

$hash = $params->hash;

try {
    $stmt = $oConexao->prepare(
        'SELECT
            id,hash,titulo,introducao
        FROM questionario
        WHERE hash=?
        LIMIT 1'
    );

    $stmt->execute(array($hash));
    $results = $stmt->fetchObject();

    if (!$results) {
        throw new Exception('Não encontrado', 404);
    }

    $stmt = $oConexao->prepare(
        'SELECT
            id,titulo,tipo,obrigatoria
        FROM pergunta
        WHERE idquestionario=?'
    );

    $stmt->execute(array($results->id));
    $results->pergunta = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;
    foreach($results->pergunta as $pergunta){
        $stmt = $oConexao->prepare(
            'SELECT
                rp.id,rp.titulo,rp.respostacurta,
                (select count(*) qtd
                    from resposta_cliente rc
                    where rc.idresposta = rp.id
                ) as qtd
            FROM resposta rp
            WHERE idpergunta=?'
        );

        $stmt->execute(array($pergunta['id']));
        $resposta = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //associar resposta da pergunta
        $results->pergunta[$count]['resposta'] = $resposta;
        $count++;

    }

    http_response_code(200);
    $response = $results;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
