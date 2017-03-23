<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

//phpmailer
include_once BASE_DIR.'/utils/phpmailer/class.phpmailer.php';
include_once BASE_DIR.'/utils/phpmailer/class.smtp.php';

if ($params->email != '') {
    switch ($params->subject) {
        case 'general':
            $subject = 'Geral (Serviços, Empresa, Forma de pagamento e Agendamento...)';
            break;

        case 'suggestions':
            $subject = 'Sugestões';
            break;

        case 'suport':
            $subject = 'Suporte';
            break;

        case 'others':
            $subject = 'Outros assuntos';
            break;

        default:
            $subject = 'Geral (Serviços, Empresa, Forma de pagamento e Agendamento...)';
            break;
    }

    /**
     * layout send e-mail for establishment.
     *
     * @subject
     * @message
     * @email recipient(destinatário)
     * @email sender(remetente)
     * @name
     */
    $message = sendContactEstablishment($subject, $params->message, $params->sender, $params->email, $params->name);

    /*send e-mail for establishment*/
    $sendEmail = 0;
    if (envia_email($params->sender, 'Marketplace, página de contato via plataforma Markday', $message, 'naoresponda@markday.com.br', $params->name)) {
        ++$sendEmail;
    }

    if ($sendEmail >= 1) {
        $msg['status'] = 'success';
        $msg['message'] = 'Mensagem enviada com sucesso! aguarde, em breve seu contato será respondido.';

        echo json_encode($msg);
    } else {
        $msg['status'] = 'error';
        $msg['message'] = 'Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde';

        echo json_encode($msg);
    }
} else {
    echo '{ "status": "error",  "message": "Email não informado, entre em contato com nossa equipe de suporte e informe ocorrido" }';
}
