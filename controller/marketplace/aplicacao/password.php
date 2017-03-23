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
        $params->email
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT id,nome,email,cpf
		FROM cliente
		WHERE 
            email=upper(?)
		AND
			status=1'
    );
    $stmt->execute(array(
        $params->email
    ));
    $results = $stmt->fetchObject();

    if($results){
        
        $code = microtime();
        $token = base64_encode($params->email.'@@@'.$code);
        $host = $_SERVER['REMOTE_ADDR'];
        $send = 0;
        $url = URL_APP. '/alterar-senha/' . $token;

        /**
         * layout send e-mail cliente.
         *
         * @login      login ou nome do cliente
         * @email      e-mail do cliente(solicitante)
         * @url        url para o cliente mudar a senha
         */
        $email_layout = sendPasswordReset($results->nome, $results->email, $url);
        $send = 0;
        if (envia_email(
                $results->email, 
                'Redefina sua senha', 
                $email_layout, 
                EMAIL_NOREPLAY, 
                $results->nome
        )) {
            $send++;
        }

        if($send){
            $stmt = $oConexao->prepare(
                'INSERT INTO 
                    cliente_token(token,idcliente,ip,dataenvio,dataativacao) 
                    VALUES(?,?,?,NOW(),DATE_ADD(NOW(), INTERVAL 3 DAY))'
            );
            $stmt->execute(array(
                $token,
                $results->id,
                $host
            ));
            http_response_code(200);
            $response->success = 'Foi enviado ao seu e-mail instruções para redefinição de sua senha';
        }else{
            throw new Exception('Favor tente novamente, e-mail de recuperação não enviado até o momento', 404);
        }
    }else{
        throw new Exception('E-mail não encontrado na plataforma', 404);
    } 

} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
