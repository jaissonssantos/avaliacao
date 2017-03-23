<?php

header('Content-type: application/json');
use Utils\Conexao;
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));

if(!isset($params->geo)){
    $params->geo = null;
}

try {

    $stmt = $oConexao->prepare('SELECT DISTINCT es.id, es.hash, es.nomefantasia, es.imagem, es.sobre,
            b.nome as segmento, es.cep, es.logradouro, es.numero, es.complemento,
            es.bairro, estd.sigla as estado, cid.nome as cidade, ep.id as idpage
            FROM estabelecimento es
            LEFT JOIN segmento b ON(es.idsegmento = b.id)
            LEFT JOIN estado estd ON(es.idestado = estd.idestado)
            LEFT JOIN cidade cid ON(es.idcidade = cid.idcidade)
            LEFT JOIN estabelecimento_page ep ON(ep.idestabelecimento = es.id)
            WHERE es.status = 1
                AND (
                        es.id IN(select idestabelecimento from tags where nome LIKE :services)    
                        OR es.nomefantasia LIKE :services
                    )
            ORDER BY es.idplano DESC');

    $stmt->bindValue('services', "%{$params->services}%", PDO::PARAM_STR);
    $stmt->bindValue('geo', "%{$params->geo}%", PDO::PARAM_STR);
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach($companies as &$estabelecimento) {
        $estabelecimento->imagem = STORAGE_URL . '/estabelecimento/' . $estabelecimento->imagem;
    }

    // Formas de Pagagmento
    $stmtpayment = $oConexao->prepare('SELECT fp.nome, fp.icone
            FROM formapagamento fp
            LEFT JOIN estabelecimento_formapagamento ef ON(ef.id = ef.idformapagamento)
            WHERE ef.id = ?');
    
    // Serviços
    $stmtservice = $oConexao->prepare('SELECT hash,nome,descricao,valor,valorpromocao,promocao
            FROM servico
            WHERE idestabelecimento = ? LIMIT 5');

    $filter_services = [];            
    foreach ($companies as &$company) {
        $stmtpayment->execute(array($company->id));
        $stmtservice->execute(array($company->id));
        $company->pagamento = $stmtpayment->fetchAll(PDO::FETCH_OBJ);
        $company->services = $stmtservice->fetchAll(PDO::FETCH_OBJ);
        $stmtservice->execute(array($company->id));
        $services = $stmtservice->fetchAll(PDO::FETCH_ASSOC);
        
        $filter_services = array_merge($filter_services, array_column($services,'nome'));
    }


    http_response_code(200);
    $response = array(
        'results' => $companies,
        'filter_service' => array_unique($filter_services)
    );

} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
}

echo json_encode($response);
