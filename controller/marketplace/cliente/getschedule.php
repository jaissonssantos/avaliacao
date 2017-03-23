<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    $uid = isset($_SESSION['ang_markday_uid']) ? $_SESSION['ang_markday_uid'] : null;
    $offset = isset($params->offset) && $params->offset > 0
                        ? $params->offset
                        : 0;
    $limit = isset($params->limit) && $params->limit < 200
                        ? $params->limit
                        : 200;

    if (!isset(
        $params->filter
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    if($params->filter==1){
        $filter = ' AND ag.status=5';
    }else if($params->filter==3){
                $filter = ' AND
                                ag.horainicial >= now() - INTERVAL 7 DAY
                            AND
                                ag.horainicial <= now() + INTERVAL 7 DAY';
            }else{
                $filter=null;
    }

    $stmt = $oConexao->prepare(
        'SELECT ag.id,ag.hash,ag.horainicial as data,
                DATE_FORMAT(ag.horainicial,"%d") as dia,
                DATE_FORMAT(ag.horainicial,"%m") as mes,
                DATE_FORMAT(ag.horainicial,"%H:%i") as horainicial,
                DATE_FORMAT(ag.horafinal,"%H:%i") as horafinal,
                ag.status,ag.observacao,
                pf.nome as profissional,
                sv.nome as servico,sv.hash as hashservico,sv.sobconsulta,sv.valor,
                sv.promocao,sv.valorpromocao,
                es.id,es.hash as hashestabelecimento,es.nomefantasia,es.cep,es.logradouro, 
                es.numero,es.complemento,es.bairro,es.telefonecomercial,
                est.sigla estadosigla, 
                cd.nome as cidade,
                fg.nome as formapagamento,
                agp.valor as valorpago
        FROM agendamento ag
        LEFT JOIN agendamento_servico agsv ON (agsv.idagendamento=ag.id)
        LEFT JOIN profissional pf ON(pf.id=ag.idprofissional)
        LEFT JOIN servico sv ON (sv.id=agsv.idservico)
        LEFT JOIN estabelecimento es ON (es.id=ag.idestabelecimento)
        LEFT JOIN estado est ON (est.idestado=es.idestado)
        LEFT JOIN cidade cd ON (es.idcidade=cd.idcidade)
        LEFT JOIN formapagamento fg ON (fg.id=ag.idformapagamento)
        LEFT JOIN agendamento_pagamento agp ON (agp.idagendamento = ag.id)
        WHERE
            ag.idcliente=:uid' . $filter . '
        GROUP BY ag.hash
        ORDER BY ag.horainicial
        LIMIT :offset,:limit'
    );

    $stmt->bindParam('uid', $uid, PDO::PARAM_INT);
    $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam('limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    $count = $oConexao->prepare(
        'SELECT COUNT(ag.id)
        FROM agendamento ag
        LEFT JOIN agendamento_servico agsv ON (agsv.idagendamento=ag.id)
        LEFT JOIN profissional pf ON(pf.id=ag.idprofissional)
        LEFT JOIN servico sv ON (sv.id=agsv.idservico)
        LEFT JOIN estabelecimento es ON (es.id=ag.idestabelecimento)
        LEFT JOIN estado est ON (est.idestado=es.idestado)
        LEFT JOIN cidade cd ON (es.idcidade=cd.idcidade)
        LEFT JOIN formapagamento fg ON (fg.id=ag.idformapagamento)
        LEFT JOIN agendamento_pagamento agp ON (agp.idagendamento = ag.id)
        WHERE
            ag.idcliente=:uid' . $filter 
    );
    $count->bindParam('uid', $uid, PDO::PARAM_INT);
    $count->execute();
    $count_results = $count->fetchColumn();

    http_response_code(200);
    if (!$results && !$count_results) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }

    $response = array(
        'results' => $results,
        'count' => array(
            'results' => $count_results
        )
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