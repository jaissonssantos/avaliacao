<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
//params json
$params = json_decode(file_get_contents('php://input'));

//return JSON
$cp = array();

try {
    $stmt = $oConexao->prepare('SELECT a.hash, a.nomefantasia, a.email, a.telefonecomercial, b.nome as segmento, a.cep, a.logradouro, a.numero, a.complemento, a.bairro, c.sigla as estado, d.nome as cidade, e.template, e.background, e.exibirintroducao, e.introducao, e.tituloempresa, e.sobre, e.tituloprofissional, e.id as idpage
									FROM estabelecimento a
									LEFT JOIN segmento b ON(a.idsegmento = b.id)
									LEFT JOIN estado c ON(a.idestado = c.idestado)
									LEFT JOIN cidade d ON(a.idcidade = d.idcidade)
									LEFT JOIN estabelecimento_page e ON(e.idestabelecimento = a.id)
										WHERE
											a.hash = :hash');
    $stmt->bindValue('hash', $params->hashcompany); //hash do estabelecimento
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $i = 0;
    $c = 0;
    if ($companies) {
        foreach ($companies as $row) {
            $cp[$i]['hash'] = $row['hash'];
            $cp[$i]['nomefantasia'] = $row['nomefantasia'];
            $cp[$i]['email'] = $row['email'];
            $cp[$i]['telefone'] = $row['telefonecomercial'];
            $cp[$i]['sobre'] = $row['sobre'];
            $cp[$i]['segmento'] = $row['segmento'];
            $cp[$i]['cep'] = $row['cep'];
            $cp[$i]['logradouro'] = $row['logradouro'];
            $cp[$i]['numero'] = $row['numero'];
            $cp[$i]['complemento'] = $row['complemento'];
            $cp[$i]['bairro'] = $row['bairro'];
            $cp[$i]['cidade'] = $row['cidade'];
            $cp[$i]['estado'] = $row['estado'];
            $cp[$i]['page'] = $row['idpage'];
            $cp[$i]['template'] = $row['template'];
            $cp[$i]['background'] = $row['background'];
            $cp[$i]['exibirintroducao'] = $row['exibirintroducao'] == 1 ? true : false;
            $cp[$i]['introducao'] = $row['introducao'];
            $cp[$i]['tituloempresa'] = $row['tituloempresa'];
            $cp[$i]['tituloprofissional'] = $row['tituloprofissional'];

            //share social company
            $stmtsharesocial = $oConexao->prepare('SELECT a.tipo, a.url
														FROM estabelecimento_redesocial a
														LEFT JOIN estabelecimento b ON(a.idestabelecimento = b.id)
															WHERE
															 b.hash = :hash
														');
            $stmtsharesocial->bindValue('hash', $row['hash']); //hash do estabelecimento
            $stmtsharesocial->execute();
            $sharecompany = $stmtsharesocial->fetchAll(PDO::FETCH_ASSOC);
            if ($sharecompany) {
                foreach ($sharecompany as $s) {
                    $share['tipo'] = $s['tipo'];
                    $share['url'] = $s['url'];

                    //add item in data
                    $cp[$i]['redesocial'][] = $share;
                }
            }

            //professional in company
            $stmtprofessional = $oConexao->prepare('SELECT a.nome, a.profissao, a.foto
														FROM profissional a
														LEFT JOIN estabelecimento b ON(a.idestabelecimento = b.id)
														LEFT JOIN page_profissional c ON(c.idprofissional = a.id)
															WHERE
																b.hash = :hash
																AND
																c.idestabelecimento_page = :page');
            $stmtprofessional->bindValue('hash', $row['hash']); //hash do estabelecimento
            $stmtprofessional->bindValue('page', $row['idpage']); //id da página(template) do estabelecimento
            $stmtprofessional->execute();
            $professionalcompany = $stmtprofessional->fetchAll(PDO::FETCH_ASSOC);
            if ($professionalcompany) {
                foreach ($professionalcompany as $r) {
                    $professional['nome'] = $r['nome'];
                    $professional['profissao'] = $r['profissao'];
                    $professional['foto'] = $r['foto'];

                    //add item in data
                    $cp[$i]['professional'][] = $professional;
                }
            }

            //services category in company
            $stmtsc = $oConexao->prepare('SELECT distinct a.nome, a.id
											FROM servico_categoria a
											LEFT JOIN servico b ON(a.id = b.idservico_categoria)
											LEFT JOIN estabelecimento c ON(b.idestabelecimento = c.id)
												WHERE
													c.hash = :hash
													AND
													b.status = 1');
            $stmtsc->bindValue('hash', $row['hash']);
            $stmtsc->execute();
            $servicecategory = $stmtsc->fetchAll(PDO::FETCH_ASSOC);
            if ($servicecategory) {
                foreach ($servicecategory as $sc) {

                    //add item category service in company
                    $categoryservice['id'] = $sc['id'];
                    $categoryservice['nome'] = $sc['nome'];

                    // $cp[$i]['services'][] =  $categoryservice;

                    //services in company
                    $stmtservice = $oConexao->prepare('SELECT a.hash, a.nome, a.descricao, a.valorpororcamento, a.valor, a.promocao, a.valorpromocao, c.icone
															FROM servico a
															LEFT JOIN estabelecimento b ON(a.idestabelecimento = b.id)
															LEFT JOIN page_servico c ON(c.idservico = a.id)
															WHERE
																b.hash = :hash
																AND
																c.ativo = 1
																AND
																a.idservico_categoria = :category
																ORDER BY
																a.valor');
                    $stmtservice->bindValue('hash', $params->hashcompany); //hash do estabelecimento
                    $stmtservice->bindValue('category', $sc['id']); //id da categoria do serviço
                    $stmtservice->execute();
                    $servicecompany = $stmtservice->fetchAll(PDO::FETCH_ASSOC);
                    if ($servicecompany) {
                        foreach ($servicecompany as $l) {
                            $service['hash'] = $l['hash'];
                            $service['nome'] = $l['nome'];
                            $service['descricao'] = $l['descricao'];
                            $service['valorpororcamento'] = $l['valorpororcamento'] == 1 ? true : false;
                            $service['valor'] = $l['valor'];
                            $service['promocao'] = intval($l['promocao']);
                            $service['valorpromocao'] = $l['valorpromocao'];
                            $service['icone'] = $l['icone'];

                            //add item in data
                            $categoryservice['item'][] = $service;
                        }
                    }

                    $cp[$i]['services'][] = $categoryservice;
                    $categoryservice = array();
                }
            }

            ++$i;
        }

        echo json_encode($cp);
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
