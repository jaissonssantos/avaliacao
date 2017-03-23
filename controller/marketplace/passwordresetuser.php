<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

//phpmailer
include_once BASE_DIR.'/utils/phpmailer/class.phpmailer.php';
include_once BASE_DIR.'/utils/phpmailer/class.smtp.php';

try {
    if ($params->email != '') {
        $msg = array();

        $stmt = $oConexao->prepare('SELECT id, nome, cpf, email
                                          FROM cliente
                                                WHERE 
                                                      email = upper(:email)');
        $stmt->bindValue('email', $params->email);
        $stmt->execute();
        $cliente = $stmt->fetchObject();

        if ($cliente) {
            $codigo = microtime();
            $token = base64_encode($params->email.'@@@'.$codigo);
            $id = $cliente->id;
            $nome = $cliente->nome;
            $ip = $_SERVER['REMOTE_ADDR'];
            $email = $params->email;

            /* variables for manipulation in e-mail */
            $sendEmail = 0;
            $url = URL_APP.'password-change/'.$token;

            /**
             * layout send e-mail cliente.
             *
             * @login      login ou nome do cliente
             * @email      e-mail do cliente(solicitante)
             * @url        url para o cliente mudar a senha
             */
            $message = sendPasswordReset($nome, $email, $url);

            if (envia_email($email, 'Redefina sua senha', $message, 'naoresponda@markday.com.br', $nome)) {
                ++$sendEmail;
            }

            if ($sendEmail >= 1) {
                $msg['status'] = 'success';
                $msg['message'] = 'Foi mandado um e-mail para você com instruções de como redefinir sua senha';
                $msg['usuario'] = $cliente;

                /* register token */
                $stmt = $oConexao->prepare('INSERT INTO cliente_token(token, idcliente, ip, dataenvio, dataativacao) VALUES(?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY))');
                $stmt->bindValue(1, $token);
                $stmt->bindValue(2, $id);
                $stmt->bindValue(3, $ip);
                $isrtToken = $stmt->execute();

                if ($isrtToken) {
                    echo json_encode($msg);
                } else {
                    $msg['status'] = 'error';
                    $msg['message'] = 'E-mail de recuperação foi enviado até o momento, aguarde instantes ou solicite novamente';
                    echo json_encode($msg);
                }
            } else {
                $msg['status'] = 'error';
                $msg['message'] = 'E-mail de recuperação não enviado até o momento, aguarde instantes ou solicite novamente';
                echo json_encode($msg);
            }
        } else {
            echo '{ "status": "error", "message": "E-mail não encontrado" }';
        }

            /* close connection */
            $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
