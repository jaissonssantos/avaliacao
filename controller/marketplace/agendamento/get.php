<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $uid = isset($_SESSION['ang_markday_uid']) ? $_SESSION['ang_markday_uid'] : null;
    $hash = isset($params->hash) ? $params->hash : null;
    $service = isset($params->service) ? $params->service : null;
    $stmt = $oConexao->prepare(
        'SELECT es.id,es.hash,es.imagem,es.nomefantasia,es.sobre,es.email,es.telefonecomercial,
            es.cep,es.logradouro,es.numero,es.complemento,es.bairro,
            est.sigla as estado,cid.nome as cidade,es.localizacao
        FROM estabelecimento es
        LEFT JOIN estado est ON(es.idestado = est.idestado)
        LEFT JOIN cidade cid ON(es.idcidade = cid.idcidade)
        WHERE
            es.hash=:hash'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);

    $stmt->execute();
    $results = $stmt->fetchObject();

    //profissional
    $stmt = $oConexao->prepare(
        'SELECT pf.id,pf.nome,pf.profissao,pf.foto,
            (SELECT count(pfs.id) 
            FROM profissional_servico pfs
            LEFT JOIN servico sv ON(sv.id=pfs.idservico)
            WHERE 
                pfs.idprofissional=pf.id
            AND
                sv.hash=:service
            ) servico
        FROM profissional pf
        LEFT JOIN estabelecimento es ON(pf.idestabelecimento = es.id)
        WHERE
            es.hash=:hash
        AND
            pf.status=1
        ORDER BY pf.nome'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->bindParam('service',$service,PDO::PARAM_STR);
    $stmt->execute();
    $profissional = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $profissional_results = array();
    $count=0;
    foreach ($profissional as $p) {
        if($p['servico']==1){
            $profissional_results[$count] = $p;
            $count++;    
        }
    }

    $results->profissional = $profissional_results;


    $count=0;
    foreach ($profissional_results as $p) {
        $stmt = $oConexao->prepare(
            'SELECT pdt.dia,pdt.horainicial,pdt.horafinal
            FROM profissional_diastrabalho pdt
            LEFT JOIN profissional pf ON(pf.id = pdt.idprofissional)
            WHERE
                pdt.idprofissional=:id'
        );

        $stmt->bindParam('id',$p['id'],PDO::PARAM_INT);
        $stmt->execute();

        $atendimento = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //associar horario de atendimento ao profissional
        $results->profissional[$count]['atendimento'] = $atendimento; 
        $count++;
    }

    //servico
    $stmt = $oConexao->prepare(
        'SELECT sv.id,sv.hash,sv.nome,sv.descricao,sv.duracao,sv.sobconsulta,sv.valor,sv.promocao,sv.valorpromocao
        FROM servico sv
        LEFT JOIN estabelecimento es ON(sv.idestabelecimento = es.id)
        WHERE
            es.hash=:hash
        AND
            sv.hash=:service'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->bindParam('service',$service,PDO::PARAM_STR);
    $stmt->execute();

    $servico = $stmt->fetchObject();
    $results->servico = $servico;

    //images
    $results->imagem =  STORAGE_URL . '/estabelecimento/' . $results->imagem;

    //formas de pagamento
    $stmt = $oConexao->prepare(
        'SELECT fpg.nome, fpg.icone
        FROM formapagamento fpg
        LEFT JOIN estabelecimento_formapagamento esfg ON(esfg.idformapagamento = fpg.id)
        LEFT JOIN estabelecimento es ON(esfg.idestabelecimento = es.id)
        WHERE
            es.hash=:hash'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $pagamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results->pagamento = $pagamento;

    //funcionamento
    $stmt = $oConexao->prepare(
        'SELECT esd.dia,
        DATE_FORMAT(esd.horainicial,"%H:%i") as horainicial,
        DATE_FORMAT(esd.horafinal,"%H:%i") as horafinal
        FROM estabelecimento_diasatendimento esd
        LEFT JOIN estabelecimento es ON(esd.idestabelecimento = es.id)
        WHERE
            es.hash=:hash'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $funcionamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results->funcionamento = $funcionamento;

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
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $avaliacao = $stmt->fetchObject();
    $results->avaliacao = $avaliacao;

    //configurações do agendamento
    $stmt = $oConexao->prepare(
        'SELECT esc.campomsgpgtoobrigatorio as obrigatorio,esc.campomsgpgtodescricao as descricao
        FROM estabelecimento_configuracao esc
        LEFT JOIN estabelecimento es ON(esc.idestabelecimento = es.id)
        WHERE
            es.hash=:hash'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $configuracao = $stmt->fetchObject();
    $results->configuracao = $configuracao;

    //estabelecimento favoritado
    $stmt = $oConexao->prepare(
        'SELECT count(esf.id)
        FROM estabelecimento_favorito esf
        LEFT JOIN estabelecimento es ON(esf.idestabelecimento = es.id)
        WHERE
            es.hash=:hash
        AND
            idcliente=:idcliente'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->bindParam('idcliente',$uid,PDO::PARAM_INT);
    $stmt->execute();

    $favorito = $stmt->fetchColumn();
    $results->favorito = $favorito==1 ? true : false ;


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