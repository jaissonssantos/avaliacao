<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

//phpmailer
include_once BASE_DIR.'/utils/phpmailer/class.phpmailer.php';
include_once BASE_DIR.'/utils/phpmailer/class.smtp.php';

try {
    if (!isset(
        $params->email,
        $params->message,
        $params->subject
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    switch ($params->subject) {
        case 'general':
            $params->subject = 'Geral (Serviços, Empresa, Forma de pagamento e Agendamento...)';
            break;

        case 'suggestions':
            $params->subject = 'Sugestões';
            break;

        case 'suport':
            $params->subject = 'Suporte';
            break;

        case 'others':
            $params->subject = 'Outros assuntos';
            break;

        default:
            $params->subject = 'Geral (Serviços, Empresa, Forma de pagamento e Agendamento...)';
            break;
    }

    /**
     * layout do e-mail enviado para o estabelecimento.
     *
     * @assunto
     * @mensagem
     * @email do destinatário
     * @email do remetente
     * @nome do cliente
     */
    $email_layout = sendContactEstablishment(
                        $params->subject, 
                        $params->message, 
                        $params->sender, 
                        $params->email, 
                        $params->name
    );
    $send = 0;
    if (envia_email(
            $params->sender, 
            'Oba! Tem mensagem nova pra você sobre: '. $params->subject, 
            $email_layout, 
            EMAIL_NOREPLAY, 
            $params->name
    )) {
        $send++;
    }

    if($send){
        $response->success = 'Mensagem enviada com sucesso! aguarde, em breve seu contato será respondido';
    }else{
        throw new Exception('Ops! tente novamente mais tarde, tivemos um problema ao enviar mensagem', 400);
    }
    http_response_code(200);


} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
