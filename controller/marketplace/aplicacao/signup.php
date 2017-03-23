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
        $params->name,
        $params->phone,
        $params->email,
        $params->password
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT COUNT(id) as total
		FROM cliente
		WHERE 
            telefonecelular=? 
		OR
			email=?
		AND
			status=1'
    );
    $stmt->execute(array(
        $params->phone,
        $params->email
    ));
    $results = $stmt->fetchObject();

    if(!$results->total){
        $stmt = $oConexao->prepare(
            'INSERT INTO 
                cliente(nome,telefonecelular,email,senha,datacadastro
                )VALUES(?,?,?,?,now())'
        );
        $stmt->execute(array(
            $params->name,
            $params->phone,
            $params->email,
            sha1(SALT.$params->password)
        ));
        $params->id = $oConexao->lastInsertId('id');
        
        $_SESSION['ang_markday_uid'] = $params->id;
        $_SESSION['ang_markday_name'] = $params->name;
        $_SESSION['ang_markday_cpf'] = '';
        $_SESSION['ang_markday_email'] = $params->email;
        $_SESSION['ang_markday_thumbnail'] = STORAGE_URL . '/cliente/default.png';

        $results = array(
            'id' => $params->id,
            'nome' => $params->name,
            'email' => $params->email,
            'cpf' => '',
            'imagem' => ''
        );

        /**
         * layout send e-mail for client signup.
         *
         * @login ou nome do cliente
         * @email
         * @nome
         */
        $email_layout = sendNewRegister($params->email, $params->email, $params->name);
        $send = 0;
        if (envia_email(
                $params->email, 
                'Bem-vindo(a) ao Markday, '. $params->name, 
                $email_layout, 
                EMAIL_NOREPLAY, 
                $params->name
        )) {
            $send++;
        }

        if($send){
            $response = array(
                'results' => $results
            );
        }else{
            $response->success = 'Estamos muito satisfeito em ter você como cliente, porém sua mensagem de boas vindas não foi enviada ao seu e-mail, mais não se preocupe! Continue utilizando o Markday';
        }
        http_response_code(200);
        
    }else{
        throw new Exception('Existe um cliente ativo com os dados informados, Verifique os dados preenchidos', 404);
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
