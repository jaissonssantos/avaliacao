<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

//return JSON
$cp = array();

try {
    $stmt = $oConexao->prepare('SELECT DISTINCT a.hash, a.nomefantasia, a.sobre, b.nome as segmento, a.cep, a.logradouro, a.numero, a.complemento, a.bairro, c.sigla as estado, d.nome as cidade, e.id as idpage
									FROM estabelecimento a
									LEFT JOIN segmento b ON(a.idsegmento = b.id)
									LEFT JOIN estado c ON(a.idestado = c.idestado)
									LEFT JOIN cidade d ON(a.idcidade = d.idcidade)
									LEFT JOIN estabelecimento_page e ON(e.idestabelecimento = a.id)
										WHERE
											b.hash = :hashsegment
										ORDER BY a.idplano DESC LIMIT '.$params->limitmin.' , '.$params->limitmax.' ');
    $stmt->bindValue('hashsegment', $params->hashsegment); //segmento do estabelecimento
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $i = 0;
    if ($companies) {
        foreach ($companies as $row) {
            $cp['company'][$i]['hash'] = $row['hash'];
            $cp['company'][$i]['nomefantasia'] = $row['nomefantasia'];
            $cp['company'][$i]['sobre'] = substr($row['sobre'], 0, 280).'...';
            $cp['company'][$i]['segmento'] = $row['segmento'];
            $cp['company'][$i]['cep'] = $row['cep'];
            $cp['company'][$i]['logradouro'] = $row['logradouro'];
            $cp['company'][$i]['numero'] = $row['numero'];
            $cp['company'][$i]['complemento'] = $row['complemento'];
            $cp['company'][$i]['bairro'] = $row['bairro'];
            $cp['company'][$i]['cidade'] = $row['cidade'];
            $cp['company'][$i]['estado'] = $row['estado'];
            $cp['company'][$i]['page'] = $row['idpage'];

            //method payment company
            $stmtpayment = $oConexao->prepare('SELECT a.nome, a.icone
												FROM formapagamento a
												LEFT JOIN estabelecimento_formapagamento b ON(a.id = b.idformapagamento)
												LEFT JOIN estabelecimento c ON(b.idestabelecimento = c.id)
												WHERE
													c.hash = :hash');
            $stmtpayment->bindValue('hash', $row['hash']); //hash do estabelecimento
            $stmtpayment->execute();
            $companiespayment = $stmtpayment->fetchAll(PDO::FETCH_ASSOC);
            if ($companiespayment) {
                foreach ($companiespayment as $l) {
                    $item['nome'] = $l['nome'];
                    $item['icone'] = $l['icone'];

                    //add item in data
                    $cp['company'][$i]['pagamento'][] = $item;
                }
            }

            //imagem featured company
            $stmtimage = $oConexao->prepare('SELECT a.imagem, a.principal
												FROM estabelecimento_imagem a
												LEFT JOIN estabelecimento b ON(a.idestabelecimento = b.id)
												WHERE
													b.hash = :hash');
            $stmtimage->bindValue('hash', $row['hash']); //hash do estabelecimento
            $stmtimage->execute();
            $companiesimage = $stmtimage->fetchAll(PDO::FETCH_ASSOC);
            if ($companiesimage) {
                foreach ($companiesimage as $l) {
                    if ($l['principal'] == 1) { //status de principal(1 - sim)
                        $cp['company'][$i]['imagem'] = $l['imagem'];
                    }
                }
            } else {

                //add image for null, case not search imagem featured in company
                $cp['company'][$i]['imagem'] = null;
            }

            //services in company
            $stmtservice = $oConexao->prepare('SELECT a.hash, a.nome, a.descricao, a.valorpororcamento, a.valor, a.promocao, a.valorpromocao
												FROM servico a
												LEFT JOIN estabelecimento b ON(a.idestabelecimento = b.id)
												WHERE
													b.hash = :hash
													AND
													a.status = 1
													ORDER BY
													a.valor');
            $stmtservice->bindValue('hash', $row['hash']); //hash do estabelecimento
            $stmtservice->execute();
            $servicecompany = $stmtservice->fetchAll(PDO::FETCH_ASSOC);
            if ($servicecompany) {
                foreach ($servicecompany as $l) {
                    $service['hash'] = $l['hash'];
                    $service['nome'] = $l['nome'];
                    $service['descricao'] = $l['descricao'];
                    $service['valorpororcamento'] = $l['valorpororcamento'] == 1 ? true : false;
                    $service['valor'] = $l['valor'];
                    $service['promocao'] = $l['promocao'];
                    $service['valorpromocao'] = $l['valorpromocao'];

                    //add item in data
                    $cp['company'][$i]['services'][] = $service;
                }
            }

            ++$i;
        }

        //get total items companies for pagination
        $stmtcount = $oConexao->prepare('SELECT DISTINCT count(a.hash) as total
										FROM estabelecimento a
										LEFT JOIN segmento b ON(a.idsegmento = b.id)
										LEFT JOIN estado c ON(a.idestado = c.idestado)
										LEFT JOIN cidade d ON(a.idcidade = d.idcidade)
										LEFT JOIN estabelecimento_page e ON(e.idestabelecimento = a.id)
											WHERE
												b.hash = :hashsegment');
        $stmtcount->bindValue('hashsegment', $params->hashsegment); //segmento do estabelecimento
        $stmtcount->execute();
        $companiestotal = $stmtcount->fetchAll(PDO::FETCH_ASSOC);
        if ($companiestotal) {
            foreach ($companiestotal as $t) {
                $cp['total'] = $t['total'];
            }
        }

        echo json_encode($cp);
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
