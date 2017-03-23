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
        $stmt = $oConexao->prepare('SELECT count(id) as total
										FROM cliente
											WHERE
												telefonecelular = :telefone
												OR
												email = :email');
        $stmt->bindValue('telefone', $params->phone);
        $stmt->bindValue('email', $params->email);
        $stmt->execute();
        $total = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($total[0]['total'] == 0) {
            $stmt = $oConexao->prepare('INSERT INTO cliente (nome, telefonecelular, email, senha, datacadastro) 
											VALUES(:nome, :telefone, :email, :senha, now())');
            $stmt->bindValue('nome', $params->name);
            $stmt->bindValue('telefone', $params->phone);
            $stmt->bindValue('email', $params->email);
            $stmt->bindValue('senha', sha1(SALT.$params->password));
            $cliente = $stmt->execute();
            $id = $oConexao->lastInsertId('id');

            if ($cliente) {

                //get itens for session user
                $stmt = $oConexao->prepare('SELECT id, nome, telefonecelular, email, cpf
												FROM cliente
													WHERE
														id = :id');
                $stmt->bindValue('id', $id); //id do cliente
                $stmt->execute();
                $item = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $sendEmail = 0;
                if ($item) {
                    foreach ($item as $row) {

                        //create session local browser
                        $_SESSION['ang_markday_uid'] = $row['id'];
                        $_SESSION['ang_markday_name'] = $row['nome'];
                        $_SESSION['ang_markday_cpf'] = $row['cpf'];
                        $_SESSION['ang_markday_email'] = $row['email'];

                        /**
                         * layout send e-mail for client signup.
                         *
                         * @login
                         * @email
                         * @nome
                         */
                        $message = sendNewRegister($row['email'], $row['email'], $row['nome']);
                    }

                    /*send e-mail for client*/
                    if (envia_email($_SESSION['ang_markday_email'], 'Cadastro na plataforma Markday', $message, 'naoresponda@markday.com.br', $_SESSION['ang_markday_name'])) {
                        ++$sendEmail;
                    }

                    if ($sendEmail >= 1) {
                        $msg['status'] = 'success';
                        $msg['message'] = 'cadastro realizado com sucesso';

                        echo json_encode($msg);
                    } else {
                        $msg['status'] = 'success';
                        $msg['message'] = 'cadastro realizado com sucesso, mais não foi enviado e-mail com os detalhes de acesso a plataforma';

                        echo json_encode($msg);
                    }
                } else {
                    echo '{ "status": "error", "message": "not create session user" }';
                }
            } else {
                echo '{ "status": "error",  "message": "not create session user" }';
            }
        } else {
            echo '{ "status": "error",  "message": "Email ou Telefone já cadastrado" }';
        }

        //close connection
        $oConexao = null;
    } else {
        echo '{ "status": "error",  "message": "Email não informado, entre em contato com nossa equipe de suporte e informe ocorrido" }';
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
