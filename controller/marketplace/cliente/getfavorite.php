<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $uid = isset($_SESSION['ang_markday_uid']) ? $_SESSION['ang_markday_uid'] : null;

    $stmt = $oConexao->prepare(
        'SELECT es.id,es.hash,es.imagem,es.nomefantasia,es.sobre,es.email,es.telefonecomercial,
            es.cep,es.logradouro,es.numero,es.complemento,es.bairro,
            est.sigla as estado,cid.nome as cidade,espg.id as page
        FROM estabelecimento es
        LEFT JOIN estabelecimento_favorito esf ON(es.id = esf.idestabelecimento)
        LEFT JOIN estado est ON(es.idestado = est.idestado)
        LEFT JOIN cidade cid ON(es.idcidade = cid.idcidade)
        LEFT JOIN estabelecimento_page espg ON(espg.idestabelecimento = es.id)
        WHERE
            esf.idcliente=:uid'
    );
    $stmt->bindParam('uid',$uid,PDO::PARAM_INT);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;
    foreach($results as $row){

        //images
        $results[$count]['imagem'] = STORAGE_URL . '/estabelecimento/' . $row['imagem']; 

        //formas de pagamento
        $stmt = $oConexao->prepare(
            'SELECT fpg.nome, fpg.icone
            FROM formapagamento fpg
            LEFT JOIN estabelecimento_formapagamento esfg ON(esfg.idformapagamento = fpg.id)
            LEFT JOIN estabelecimento es ON(esfg.idestabelecimento = es.id)
            WHERE
                es.hash=:hash'
        );
        $stmt->bindParam('hash',$row['hash'],PDO::PARAM_STR);
        $stmt->execute();

        $pagamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results[$count]['pagamento'] = $pagamento; 

        //números das avaliações
        $stmt = $oConexao->prepare(
            'SELECT count(esav.id) as quantidade,avg(esav.avaliacao) as media
            FROM estabelecimento es
            LEFT JOIN estabelecimento_avaliacao esav ON(esav.idestabelecimento = es.id)
            WHERE
                es.hash=:hash
            AND
                esav.status=1'
        );
        $stmt->bindParam('hash',$row['hash'],PDO::PARAM_STR);
        $stmt->execute();

        $avaliacao = $stmt->fetchObject();
        $results[$count]['avaliacao'] = $avaliacao; 

        //servicos
        $stmt = $oConexao->prepare(
            'SELECT avg(sv.valor) as media
            FROM servico sv
            LEFT JOIN estabelecimento es ON(sv.idestabelecimento = es.id)
            WHERE
                es.hash=:hash
            AND
                sv.status=1'
        );
        $stmt->bindParam('hash',$row['hash'],PDO::PARAM_STR);
        $stmt->execute();

        $servico = $stmt->fetchObject();
        $results[$count]['servico'] = $servico; 

        //promocao
        $stmt = $oConexao->prepare(
            'SELECT count(sv.id) total
            FROM servico sv
            LEFT JOIN estabelecimento es ON(sv.idestabelecimento = es.id)
            WHERE
                es.hash=:hash
            AND
                sv.status=1
            AND
                sv.sobconsulta=0
            AND
                sv.promocao=1'
        );
        $stmt->bindParam('hash',$row['hash'],PDO::PARAM_STR);
        $stmt->execute();

        $promocao = $stmt->fetchObject();
        $results[$count]['promocao'] = intval($promocao->total); 

        $count++;
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