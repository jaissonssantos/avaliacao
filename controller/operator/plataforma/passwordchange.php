<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->email != '') {
        $stmt = $oConexao->prepare("SELECT c.id, c.login, c.email, s.nome, s.tipo 
										FROM cliente c INNER JOIN status s ON (c.status_id = s.id)
									WHERE s.nome = 'Ativo' AND s.tipo = 'cliente' AND c.email = upper(:email)");
        $stmt->bindParam('email', $params->email);
        $stmt->execute();
        $usuario = $stmt->fetchObject();

        if ($usuario) {
            $codigo = microtime();
            $token = base64_encode($params->email.'@@@'.$codigo);
            $id = $usuario->id;
            $login = $usuario->login;
            $nome = $usuario->nome;
            $ip = $_SERVER['REMOTE_ADDR'];
            $email = $params->email;

              /* inserir token no banco de dados */
              $stmt = $oConexao->prepare('INSERT INTO cliente_token(token, ip, data_envio, data_expiracao, cliente_id) VALUES(?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY), ?)');
            $stmt->bindValue(1, $token);
            $stmt->bindValue(2, $ip);
            $stmt->bindValue(3, $id);
            $isrtToken = $stmt->execute();

            if ($isrtToken) {
                /* limpar conexao */
                  $oConexao = null;

                  /* variaveis para manipulação do e-mail */
                  $sendEmail = 0;
                $url = URL_APP.'#!/password-change/'.$token;

                  /**
                   * layout send e-mail cliente.
                   *
                   * @login 	login do cliente
                   * @email 	e-mail do cliente(solicitante)
                   * @url 		url para o cliente mudar a senha
                   */
                  $message = sendPasswordReset($login, $email, $url);

                if (envia_email($email, 'Redefina sua senha', $message, 'contato@likecell.com.br', $nome)) {
                    ++$sendEmail;
                }

                if ($sendEmail >= 1) {
                    $msg = array();
                    $msg['msg'] = 'success';
                    $msg['usuario'] = $usuario;
                    echo json_encode($msg);
                } else {
                    $msg = array();
                    $msg['error'] = 'error';
                    echo json_encode($msg);
                }
            }
        } else {
            echo '{ "credentials": "null" }';
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "credentials": "null",  "error": "'.$e->getMessage().'" }';
    die();
}
