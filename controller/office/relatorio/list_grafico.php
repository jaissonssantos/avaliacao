<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();


$idresposta = $params->id;

try {
    
        $stmt = $oConexao->prepare(
            'SELECT
                rp.id,rp.titulo,rp.resposta,
                (select count(*) qtd
                    from resposta_cliente rc
                    where rc.idresposta = rp.id
                ) as qtd
            FROM resposta rp
            WHERE idpergunta=?'
        );

        $stmt->execute(array($idresposta));
        $resposta = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    $response = $resposta;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
