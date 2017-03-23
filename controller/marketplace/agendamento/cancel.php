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
    
    if (!isset(
        $params->hash
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $idcliente = $_SESSION['ang_markday_uid'];
    $nomeCliente = $_SESSION['ang_markday_name'];
    $emailCliente = $_SESSION['ang_markday_email'];

    // atualiza o agendamento com status 5
    $stmt = $oConexao->prepare('UPDATE agendamento
                SET status=5
                WHERE hash=? 
                AND
                    idcliente=?');
    $stmt->execute(array(
        $params->hash,
        $idcliente
    ));

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
    $local = $params->logradouro .',' .
             $params->complemento . ',' .
             $params->numero . ',' .
             $params->bairro . ', ' .
             $params->cidade . ', ' .
             $params->estadosigla . ', ' .
             $params->cep;

    $valor = $params->promocao ? $params->valorpromocao : $params->valor;
    if($params->sobconsulta) $valor = 'sob consulta';              
    $quando = today($params->data).', '.todayextensive($params->data);
    $estabelecimento = $params->nomefantasia;

    $email_layout = sendCancelScheduling(
        $params->profissional,
        $quando,
        $valor, 
        $params->servico,
        $estabelecimento,
        $local, 
        $emailCliente,
        $nomeCliente,
        'Cancelado' //status do agendamento
    );

    if(envia_email($emailCliente, 'Cancelamento de Agendamento', $email_layout, EMAIL_NOREPLAY, $nomeCliente)){
        $response->success = 'Agendamento cancelado com sucesso';
    }else{
        $response->success = 'Agendamento cancelado com sucesso, você pode emitir marcar um novo serviço quando quiser';
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
