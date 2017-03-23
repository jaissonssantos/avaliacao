<?php


use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    $hash = isset($params->hash) ? $params->hash : null;
    
    $date = isset($params->date) ? date_create($params->date) : null;
    $date = date_format($date, 'Y-m-d');

    $horainicial = $date .' 00:00:00';
    $horafinal   = $date .' 23:59:59';
    $idprofissional = isset($params->profissional->id) ? $params->profissional->id : null;


    $stmt = $oConexao->prepare(
        'SELECT ag.horainicial,ag.horafinal
        FROM agendamento ag
        LEFT JOIN estabelecimento es ON(ag.idestabelecimento = es.id)
        WHERE
            es.hash=:hash
        AND
            ag.status<=3
        AND
            ag.horainicial>=:horainicial
        AND
            ag.horainicial<=:horafinal
        AND
            ag.idprofissional=:idprofissional'
    );

    $stmt->execute(array(
        'hash' => $hash, 
        'horainicial' => $horainicial, 
        'horafinal' => $horafinal, 
        'idprofissional' => $idprofissional 
    ));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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