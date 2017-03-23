<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();
date_default_timezone_set( 'America/Rio_Branco' ); //Acre, Rio branco

//phpmailer
include_once BASE_DIR.'/utils/phpmailer/class.phpmailer.php';
include_once BASE_DIR.'/utils/phpmailer/class.smtp.php';

try {
    $oConexao->beginTransaction();

    //if(session_id()) throw new Exception('Usuário não autenticado', 400);

    /* Iugu
     include_once BASE_DIR.'/utils/iugu/Iugu.php';
     $iuguToken = IuguConnect::token(array(
         'account_id' => '76a9f9d99f5ade6e093d255d95d97726',
         'method' => 'credit_card',
         'data' => array(
             'number' => '4111111111111111',
             'verification_value' => '123',
             'first_name' => 'joao',
             'last_name' => 'silva',
             'month' => 12,
             'year' => 2017
         )
     ));

     $iuguCharge =  IuguConnect::charge(array(
         'token' => $iuguToken['id'],
         'email' => 'mark.doe@markday.com',
         'items' => array(
             'description' => 'Escova de cristal',
             'quantity' => 1,
             'price_cents' => 15000
         ),
         'payer' => array(
         'name' => 'jaisson santos',
         'phone_prefix' => '68',
         'phone' => '32253186',
         'email' => 'jaissonssantos@gmail.com',
         'address' => array(
         'street' => 'Rua Tal',
         'number' => 700,
         'city' => 'São Paulo',
         'state' => 'SP',
         'country' => 'Brasil',
          'zip_code' => '12122-000'
         )
         )
     ));

     $iuguMarketplaceCreate = IuguConnect::marketplaceCreate([
         'name' => 'Kambo beta',
         'commission_percent' => '10'
     ]);
     $response = $iuguMarketplaceCreate;

     $response = $iuguCharge;
     */

    
    
    if (!isset(
        $params->idEstabelecimento,
        $params->idProfissional,
        $params->idServico,
        $params->formaPagamento,
        $params->horario
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    if(!isset($params->observacao)){
        $params->observacao = null;
    }

    // Carrega o serviço e dados associados
    $stmt = $oConexao->prepare('SELECT p.nome as profissional,s.nome,s.valor,s.promocao,s.valorpromocao,
                                s.duracao,s.sobconsulta,e.nomefantasia as estabelecimento,e.logradouro,e.numero,e.bairro,e.complemento,
                                e.cep,c.nome as cidade, estd.nome as estado
                                FROM profissional_servico ps
                                    INNER JOIN servico s ON (ps.idservico = s.id)
                                    INNER JOIN profissional p ON (ps.idprofissional = p.id)
                                    INNER JOIN estabelecimento e ON (p.idestabelecimento = e.id)
                                    INNER JOIN cidade c ON (e.idcidade = c.idcidade)
                                    INNER JOIN estado estd ON (c.idestado = estd.idestado)
                                WHERE ps.idprofissional = ? AND ps.idservico = ?
                                LIMIT 1'
                            ); 
    $stmt->execute(array($params->idProfissional, $params->idServico));
    $servico = $stmt->fetchObject();
    if(!$servico) {
         throw new Exception('Desculpe, o profissional selecionado não atende mais este serviço', 400);
    }

    $valor = $servico->promocao ? $servico->valorpromocao : $servico->valor;
    $estabelecimento = $servico->estabelecimento;

    // Converte os horários de agendamento
    $horainicial = strstr($params->horario, ' (', true); // remove a timzeone
    $horainicial = date('Y-m-d H:i:s', strtotime($horainicial));
    $horafinal = date('Y-m-d H:i:s', strtotime($params->horario.'+'.$servico->duracao . ' minutes'));
    $hash = generatehash();

    $idcliente = $_SESSION['ang_markday_uid'];
    $nomeCliente = $_SESSION['ang_markday_name'];
    $emailCliente = $_SESSION['ang_markday_email'];

    // Cadastra o agendamento com status 2
    $stmt = $oConexao->prepare('INSERT INTO
                 agendamento(hash,idcliente,idprofissional,horainicial,horafinal,status,idestabelecimento,
                 idformapagamento,observacao,datacadastro
                ) VALUES (?,?,?,?,?,2,?,?,?,now())');
    $stmt->execute(array(
        $hash,
        $idcliente,
        $params->idProfissional,
        $horainicial, 
        $horafinal,
        $params->idEstabelecimento,
        $params->formaPagamento,
        $params->observacao
    ));
    $idAgendamento = $oConexao->lastInsertId('id');

    // Associa o agendamento ao serviço
    $stmt = $oConexao->prepare('INSERT INTO
                 agendamento_servico(idservico,idagendamento
                ) VALUES (?,?)');
    $stmt->execute(array(
        $params->idServico,
        $idAgendamento
    ));

    // Associa o agendamento a forma de pagamento
    if(!$servico->sobconsulta){
        $hashPagamento = generatehash();
        $stmt = $oConexao->prepare('INSERT INTO
                 agendamento_pagamento(idagendamento,codigo,valor,datacadastro
                ) VALUES (?,?,?,now())');
        $stmt->execute(array(
            $idAgendamento,
            $hashPagamento,
            $valor
        ));
    }

    // Verifica se o cliente já foi cadastrado no estabelecimento
    $stmt = $oConexao->prepare('SELECT count(idcliente) total
                 FROM cliente_estabelecimento
                WHERE idcliente=? LIMIT 1');
    $stmt->execute(array($idcliente));
    $clienteExiste = $stmt->fetchColumn();
    if(!$clienteExiste){
        $stmt = $oConexao->prepare('INSERT INTO
                 cliente_estabelecimento(idcliente,idestabelecimento,status
                ) VALUES (?,?,1)');
        $stmt->execute(array(
            $idcliente,
            $params->idEstabelecimento
        ));
    }

    /**
     * layout send e-mail for client itens schedulinhg.
     *
     * @profissional
     * @quando(data e horário)
     * @preço
     * @servico
     * @estabelecimento
     * @onde
     * @email
    */
    
    $local = $servico->logradouro .',' .
             $servico->complemento . ',' .
             $servico->numero . ',' .
             $servico->bairro . ', ' .
             $servico->cidade . ', ' .
             $servico->estado . ', ' .
             $servico->cep;
    
    if($servico->sobconsulta) $valor = 'sob consulta';              
    $quando = today($horainicial).', '.todayextensive($horainicial);

    $email_layout = sendNewScheduling(
        $servico->profissional,
        $quando,
        $valor, 
        $servico->nome,
        $estabelecimento,
        $local, 
        $emailCliente,
        $nomeCliente
    );

    if(envia_email($emailCliente, 'Confirmação de Agendamento', $email_layout, EMAIL_NOREPLAY, $nomeCliente)){
        $response->success = 'Agendamento realizado com sucesso';
    }else{
        $response->success = 'Agendamento realizado com sucesso, você pode emitir comprovante quando quiser do seus agendamentos';
    }
    $oConexao->commit();
    http_response_code(200);

} catch (PDOException $e) {
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    $oConexao->rollBack();
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}


echo json_encode($response);
