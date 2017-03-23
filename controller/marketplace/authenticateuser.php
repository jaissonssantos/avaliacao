<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->email != '') {
        $stmt = $oConexao->prepare('SELECT c.id, c.nome, c.cpf, c.email, c.imagem
										FROM cliente c 
											WHERE 
												( c.cpf = upper(:email) 
												OR 
												c.email = upper(:email) ) 
												AND 
												c.senha = upper(:senha)
												AND
												c.status=1');
        $stmt->bindValue('email', $params->email);
        $stmt->bindValue('senha', sha1(SALT.$params->password));
        $stmt->execute();
        $cliente = $stmt->fetchObject();

        if ($cliente) {

            //create session local browser
            $_SESSION['ang_markday_uid'] = $cliente->id;
            $_SESSION['ang_markday_name'] = $cliente->nome;
            $_SESSION['ang_markday_cpf'] = $cliente->cpf;
            $_SESSION['ang_markday_email'] = $cliente->email;
            $_SESSION['ang_markday_thumbnail'] = STORAGE_URL . '/cliente/' . $cliente->imagem;

            $stmt = $oConexao->prepare(
                'UPDATE cliente 
					SET datalogin=now()
				WHERE id=:id'
            );
            $stmt->execute(array('id' => $cliente->id));

            echo json_encode($cliente);
        } else {
            echo '{ "credentials": "null", "message": "Favor verifique os dados, credenciais informada está incorreta." }';
        }

        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
