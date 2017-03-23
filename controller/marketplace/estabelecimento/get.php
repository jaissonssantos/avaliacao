<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $hash = isset($params->hash) ? $params->hash : null;
    $stmt = $oConexao->prepare(
        'SELECT es.id,es.hash,es.nomefantasia,es.sobre,es.email,es.telefonecomercial,
            seg.nome as segmento,es.cep,es.logradouro,es.numero,es.complemento,es.bairro,
            est.sigla as estado,cid.nome as cidade,es.localizacao,espg.id as pagina
        FROM estabelecimento es
        LEFT JOIN segmento seg ON(es.idsegmento = seg.id)
        LEFT JOIN estado est ON(es.idestado = est.idestado)
        LEFT JOIN cidade cid ON(es.idcidade = cid.idcidade)
        LEFT JOIN estabelecimento_page espg ON(espg.idestabelecimento = es.id)
        WHERE
            es.hash=:hash'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);

    $stmt->execute();
    $results = $stmt->fetchObject();

    if(isset($results->pagina)){
        //pagina
        $stmt = $oConexao->prepare(
            'SELECT ep.template,ep.background,ep.exibirintroducao,ep.introducao,ep.tituloempresa,ep.sobre,ep.tituloprofissional
            FROM estabelecimento_page ep
            LEFT JOIN estabelecimento es ON(ep.idestabelecimento = es.id)
            WHERE
                es.hash=:hash'
        );
        $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
        $stmt->execute();

        $tema = $stmt->fetchObject();
        $results->tema = $tema;

        //rede sociais
        $stmt = $oConexao->prepare(
            'SELECT ers.tipo,ers.url
            FROM estabelecimento_redesocial ers
            LEFT JOIN estabelecimento es ON(ers.idestabelecimento = es.id)
            WHERE
                es.hash=:hash');
        $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
        $stmt->execute();

        $redesocial = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results->redesocial = $redesocial;

    }

    //profissional
    $stmt = $oConexao->prepare(
        'SELECT pf.nome,pf.profissao,pf.foto
        FROM profissional pf
        LEFT JOIN estabelecimento es ON(pf.idestabelecimento = es.id)
        WHERE
            es.hash=:hash
        AND
            pf.status=1'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $profissional = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results->profissional = $profissional;

    //tags
    $stmt = $oConexao->prepare(
        'SELECT tag.nome
        FROM tags tag
        LEFT JOIN estabelecimento es ON(tag.idestabelecimento = es.id)
        WHERE
            es.hash=:hash');
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results->tags = $tags;

    //images
    $stmt = $oConexao->prepare(
        'SELECT esimg.imagem as arquivo
        FROM estabelecimento_imagem esimg
        LEFT JOIN estabelecimento es ON(esimg.idestabelecimento = es.id)
        WHERE
            es.hash=:hash
        ORDER BY principal DESC,ordem ASC'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $imagem = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results->imagem = $imagem;

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

    //dias de trabalho
    $stmt = $oConexao->prepare(
        'SELECT esdatd.dia, 
            date_format(esdatd.horainicial, "%H:%s") as horainicial, 
            date_format(esdatd.horafinal, "%H:%s") as horafinal
        FROM estabelecimento es
        LEFT JOIN estabelecimento_diasatendimento esdatd ON(esdatd.idestabelecimento = es.id)
        WHERE
            es.hash=:hash'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $atendimento = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results->atendimento = $atendimento;

    //comentários
    $stmt = $oConexao->prepare(
        'SELECT esav.avaliacao,esav.comentario as mensagem,esav.data,esav.status,c.nome as cliente
        FROM estabelecimento es
        LEFT JOIN estabelecimento_avaliacao esav ON(esav.idestabelecimento = es.id)
        LEFT JOIN cliente c ON(esav.idcliente = c.id)
        WHERE
            es.hash=:hash
        AND
            esav.status=1
        ORDER BY esav.data DESC'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $comentario = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $i=0;
    foreach ($comentario as $c) {
        $results->comentario[$i]->nota = $c['nota'];
        $results->comentario[$i]->mensagem = $c['mensagem'];
        $results->comentario[$i]->data = calculatortimestamp(strtotime($c['data']));
        $results->comentario[$i]->cliente = $c['cliente'];
        $results->comentario[$i]->status = $c['status'];
        $i++;
    }

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

    //categorias -> serviços
    $stmt = $oConexao->prepare(
        'SELECT DISTINCT(svcc.nome), svcc.id
        FROM servico_categoria svcc
        LEFT JOIN servico svc ON(svcc.id = svc.idservico_categoria)
        LEFT JOIN estabelecimento es ON(svc.idestabelecimento = es.id)
        WHERE
            es.hash=:hash
        AND
            svc.status=1'
    );
    $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
    $stmt->execute();

    $categoria = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results->categoria = $categoria;

    $count_segment = 0;
    foreach($results->categoria as $row){
        $stmt = $oConexao->prepare(
			'SELECT sv.hash,sv.nome,sv.descricao,sv.duracao,sv.sobconsulta,sv.valor,sv.promocao,sv.valorpromocao
			FROM servico sv
			LEFT JOIN estabelecimento es ON(sv.idestabelecimento = es.id)
			WHERE
				es.hash=:hash
			AND
				sv.idservico_categoria=:categories
            AND
                sv.status=1
			ORDER BY sv.valor'
		);

        $stmt->bindParam('hash',$hash,PDO::PARAM_STR);
		$stmt->bindParam('categories',$row['id'],PDO::PARAM_INT);
		$stmt->execute();

        $servico = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //associar serviço a categoria
        $results->categoria[$count_segment]['servico'] = $servico; 
        $count_segment++;
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