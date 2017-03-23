<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//get session local browser
$uid = $_SESSION['ang_markday_uid'];
$cpf = $_SESSION['ang_markday_cpf'];
$email = $_SESSION['ang_markday_email'];

//params
$params = json_decode(file_get_contents('php://input'));

//phpmailer
include_once BASE_DIR.'/utils/phpmailer/class.phpmailer.php';
include_once BASE_DIR.'/utils/phpmailer/class.smtp.php';

//identify edit or new scheduling
$scheduled = ($params->hashscheduled != '' || $params->hashscheduled != 'undefined' ? $params->hashscheduled : '');
//convert date format SGDB
$intervaltime = $params->intervaltime;

$dtbegin = strstr($params->timebegin, ' (', true);
$dtbegin = strtotime($dtbegin);
$params->timebegin = date('Y-m-d H:i:s', $dtbegin);
$params->timeend = date('Y-m-d H:i:s', strtotime($params->timebegin."+{$intervaltime} minutes")); //ex.: +5 minutes

//return JSON
$sc = array();
$scemail = array();

try {
    if ($params && $params->methodpay == 2 && $params->establishment != '') { //methodpay(2 - pagamento no estabelecimento)

        //get id company
        $stmtcompany = $oConexao->prepare('SELECT id, hash FROM estabelecimento WHERE hash = :hash');
        $stmtcompany->bindValue('hash', $params->establishment);
        $stmtcompany->execute();
        $company = $stmtcompany->fetchObject();

        //get id services
        $stmtservices = $oConexao->prepare('SELECT id, hash, valor, promocao, valorpromocao FROM servico WHERE hash = :hash AND idestabelecimento = :establishment');
        $stmtservices->bindValue('hash', $params->services);
        $stmtservices->bindValue('establishment', $company->id);
        $stmtservices->execute();
        $services = $stmtservices->fetchObject();

        if ($company->id != '' && $services->id != '') {
            if ($scheduled == '') { // insert scheduling

                //generate hash
                $hash = uniqid();

                $stmt = $oConexao->prepare('INSERT INTO agendamento (hash, idcliente, idprofissional, horainicial, horafinal, status, idestabelecimento, idformapagamento, observacao, datacadastro) 
												VALUES(:hash, :uid, :professional, :timebegin, :timeend, :status, :establishment, :methodpay, :notice, now())');
                $stmt->bindValue('hash', $hash);
                $stmt->bindValue('uid', $uid);
                $stmt->bindValue('professional', $params->professional);
                $stmt->bindValue('timebegin', $params->timebegin);
                $stmt->bindValue('timeend', $params->timeend);
                $stmt->bindValue('status', 2); //status(2 - agendado online)
                $stmt->bindValue('establishment', $company->id);
                $stmt->bindValue('methodpay', 4); //status(4 - no estabelecimento)
                $stmt->bindValue('notice', $params->notice);
                $scheduling = $stmt->execute();
                $id = $oConexao->lastInsertId('id');

                //insert scheduling_service
                $stmt = $oConexao->prepare('INSERT INTO agendamento_servico (idservico, idagendamento) 
												VALUES(:services, :scheduling)');
                $stmt->bindValue('services', $services->id);
                $stmt->bindValue('scheduling', $id);
                $scheduling = $stmt->execute();

                //insert scheduling_payment
                $code = generatehash();
                $paid = ($services->promocao == 0 ? $services->valor : $services->valorpromocao);
                $stmt = $oConexao->prepare('INSERT INTO agendamento_pagamento (idagendamento, codigo, valor, datacadastro) 
												VALUES(:scheduling, :code, :paid, now())');
                $stmt->bindValue(':scheduling', $id); //id agendamento
                $stmt->bindValue(':code', $code); //code(código de retorno da transação efetuada pela plataforma de pagamento)
                $stmt->bindValue(':paid', $paid); //valor pago no agendamento
                $pay = $stmt->execute();

                //check client in establishment
                $stmtclientestablishment = $oConexao->prepare('SELECT count(idcliente) as total FROM cliente_estabelecimento WHERE idcliente = :uid');
                $stmtclientestablishment->bindValue('uid', $uid);
                $stmtclientestablishment->execute();
                $ce = $stmtclientestablishment->fetchObject();

                if ($ce->total == 0) {
                    //insert client in establishment
                    $newclient = $oConexao->prepare('INSERT INTO cliente_estabelecimento (idcliente, idestabelecimento, status)
														VALUES(:uid, :establishment, :status)');
                    $newclient->bindValue('uid', $uid);
                    $newclient->bindValue('establishment', $company->id);
                    $newclient->bindValue('status', 1); //active(ativo)
                    $newclient->execute();
                }
            } else { //edit scheduling

                //get data scheduling
                $stmtscheduling = $oConexao->prepare('SELECT id, hash FROM agendamento WHERE hash = :hash AND idestabelecimento = :establishment');
                $stmtscheduling->bindValue('hash', $scheduled);
                $stmtscheduling->bindValue('establishment', $company->id);
                $stmtscheduling->execute();
                $scheduledtime = $stmtscheduling->fetchObject();

                //set id scheduling
                $id = $scheduledtime->id;

                $stmt = $oConexao->prepare('UPDATE agendamento SET idprofissional = :professional, horainicial = :timebegin, horafinal = :timeend, status = :status, idformapagamento = :methodpay, observacao = :notice, datacadastro = now() WHERE id = :scheduling');
                $stmt->bindValue('professional', $params->professional);
                $stmt->bindValue('timebegin', $params->timebegin);
                $stmt->bindValue('timeend', $params->timeend);
                $stmt->bindValue('status', 2); //status(2 - agendado online)
                $stmt->bindValue('methodpay', 4); //status(4 - no estabelecimento)
                $stmt->bindValue('notice', $params->notice);
                $stmt->bindValue('scheduling', $scheduledtime->id);
                $scheduling = $stmt->execute();

                //update scheduling_service
                $stmt = $oConexao->prepare('UPDATE agendamento_servico SET idservico = :services WHERE idagendamento = :scheduling');
                $stmt->bindValue('services', $services->id);
                $stmt->bindValue('scheduling', $scheduledtime->id);
                $scheduling = $stmt->execute();

                //insert scheduling_payment
                $paid = ($services->valorpromocao == 0.00 ? $services->valor : $services->valorpromocao);
                $stmt = $oConexao->prepare('UPDATE agendamento_pagamento SET valor = :paid, datacadastro = now() WHERE idagendamento = :scheduling');
                $stmt->bindValue(':scheduling', $scheduledtime->id); //id agendamento
                $stmt->bindValue(':paid', $paid); //valor pago no agendamento
                $pay = $stmt->execute();
            }

            if ($scheduling && $pay) {

                //details scheduling
                $stmt = $oConexao->prepare('SELECT a.horainicial, a.horafinal, a.idestabelecimento, a.idprofissional, a.idformapagamento, b.nomefantasia, b.email, b.telefonecomercial, b.cep, 
											   b.logradouro, b.numero, b.complemento, b.bairro, c.sigla as estado, d.nome as cidade,
											   e.nome as profissional, e.email as pemail, g.nome as servico, g.valorpororcamento, g.valor, g.promocao, g.valorpromocao,
											   h.nome as formapagamento,
											   i.nome as cnome, i.email as cemail
											FROM agendamento a
											LEFT JOIN estabelecimento b ON(a.idestabelecimento = b.id)
											LEFT JOIN estado c ON(b.idestado = c.idestado)
											LEFT JOIN cidade d ON(b.idcidade = d.idcidade)
											LEFT JOIN profissional e ON(a.idprofissional = e.id)
											LEFT JOIN agendamento_servico f ON(a.id = f.idagendamento)
											LEFT JOIN servico g ON(f.idservico = g.id)
											LEFT JOIN formapagamento h ON(a.idformapagamento = h.id)
											LEFT JOIN cliente i ON(a.idcliente = i.id)
												WHERE 
													a.id = :agendamento');
                $stmt->bindValue('agendamento', $id);
                $stmt->execute();
                $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($details) {
                    foreach ($details as $row) {
                        $sc['nomefantasia'] = $row['nomefantasia'];
                        $sc['email'] = ($row['email'] != '' ? $row['email'] : '-');
                        $sc['telefonecomercial'] = ($row['telefonecomercial'] != '' ? $row['telefonecomercial'] : '000000000');
                        $sc['cep'] = $row['cep'];
                        $sc['logradouro'] = $row['logradouro'];
                        $sc['numero'] = $row['numero'];
                        $sc['complemento'] = $row['complemento'];
                        $sc['bairro'] = $row['bairro'];
                        $sc['estado'] = $row['estado'];
                        $sc['cidade'] = $row['cidade'];
                        $sc['profissional'] = $row['profissional'];
                        $sc['servico'] = $row['servico'];
                        $sc['valorpororcamento'] = $row['valorpororcamento'];
                        $sc['valor'] = $row['valor'];
                        $sc['promocao'] = intval($row['promocao']);
                        $sc['valorpromocao'] = $row['valorpromocao'];
                        $sc['quando'] = today($row['horainicial']).', '.todayextensive($row['horainicial']);
                        $sc['formapagamento'] = $row['formapagamento'];
                        $sc['horainicial'] = date('H:i', strtotime($row['horainicial']));
                        $sc['horafinal'] = date('H:i', strtotime($row['horafinal']));

                        /*set item for send e-mail*/
                        $scemail['profissional'] = $row['profissional'];
                        $scemail['quando'] = $sc['quando'];
                        $scemail['servico'] = $row['servico'];
                        $scemail['estabelecimento'] = $row['nomefantasia'];
                        if (intval($row['valorpororcamento']) == 1) {
                            $scemail['valor'] = 'Valor a ser pago após a analise profissional';
                        } else {
                            if (intval($row['promocao']) == 0) {
                                $scemail['valor'] = $row['valor'];
                            } else {
                                $scemail['valor'] = $row['valorpromocao'];
                            }
                        }
                        $scemail['onde'] = $row['logradouro'].', '.$row['numero'].', '.$row['complemento'].' '.$row['bairro'].', '.$row['cidade'].', '.$row['estado'].' - '.$row['cep'];
                        $scemail['email'] = $row['cemail'];
                        $scemail['nome'] = $row['cnome'];

                        /**
                         * layout send e-mail for client itens schedulinhg.
                         *
                         * @professional
                         * @when
                         * @price
                         * @service
                         * @establishment
                         * @where
                         * @email
                         */
                        $message = sendNewScheduling($scemail['profissional'], $scemail['quando'], $scemail['valor'], $scemail['servico'], $scemail['estabelecimento'], $scemail['onde'], $scemail['email'], $scemail['nome']);

                        if ($row['pemail'] != '') {
                            /*send e-mail for client*/
                            if (envia_email($row['pemail'], 'Agendamento confirmado', $message, 'naoresponda@markday.com.br', $row['profissional'])) {
                                ++$sendEmail;
                            }
                        }

                        if ($row['cemail'] != '') {
                            /*send e-mail for client*/
                            if (envia_email($row['cemail'], 'Agendamento confirmado', $message, 'naoresponda@markday.com.br', $row['cnome'])) {
                                ++$sendEmail;
                            }
                        }

                        if ($sendEmail >= 1) {
                            $sc['status'] = 'success';
                            $sc['message'] = 'Pronto! Seu agendamento está confirmado';

                            echo json_encode($sc);
                        } else {
                            $sc['status'] = 'success';
                            $sc['message'] = 'Pronto! Seu agendamento está confirmado, mais não foi enviado e-mail com os detalhes do agendamento por isso anote os dados abaixo';

                            echo json_encode($sc);
                        }
                    }
                } else {
                    echo '{ 
							"status": "error",  
							"message": "Ops! Ocorreu um problema ao tentar confirmar seu agendamento", 
							"errors": 
								[ 
									{ 
										"error": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde"  
									}
								] 
						}';
                }
            } else {
                echo '{ "status": "error",  "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
            }

            //close connection
            $oConexao = null;
        }
    } else {
        echo '{ "status": "error",  "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
