<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $uid = isset($_SESSION['ang_markday_uid']) ? $_SESSION['ang_markday_uid'] : null;

    //avaliações
    $stmt = $oConexao->prepare(
        'SELECT es.id,es.nomefantasia,
            esav.comentario,esav.data,esav.status
        FROM estabelecimento es
        LEFT JOIN estabelecimento_avaliacao esav ON(esav.idestabelecimento = es.id)
        WHERE
            esav.idcliente=:idcliente'
    );
    $stmt->bindParam('idcliente',$uid,PDO::PARAM_INT);
    $stmt->execute();
    $avaliacao = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $i=0;
    foreach ($avaliacao as $a) {
        $results[$i]->id = $a['id'];
        $results[$i]->nomefantasia = $a['nomefantasia'];
        $results[$i]->data = calculatortimestamp(strtotime($a['data']));
        $results[$i]->comentario = $a['comentario'];
        $results[$i]->status = $a['status'];
        $i++;
    }

    http_response_code(200);
    if (!$results) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }
    $response = array(
        'results' => $results
    );

} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);