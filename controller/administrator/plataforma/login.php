<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
//params
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->email)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare('SELECT a.id, a.nome, a.login, a.email, a.idestabelecimento, a.perfil, c.nome as plano
									FROM usuario a
									LEFT JOIN estabelecimento b ON (a.idestabelecimento = b.id)
									LEFT JOIN plano c ON (b.idplano = c.id)
								WHERE upper(a.login) = upper(:email) OR upper(a.email) = upper(:email) AND a.senha = :senha AND a.status = 1');
    $stmt->bindValue('email', $params->email);
    $stmt->bindValue('senha', sha1(SALT.$params->password));
    $stmt->execute();
    $usuario = $stmt->fetchObject();
    $oConexao = null;

    if ($usuario) {
        //create session local browser
        $_SESSION['ang_plataforma_uid'] = $usuario->id;
        $_SESSION['ang_plataforma_nome'] = $usuario->nome;
        $_SESSION['ang_plataforma_login'] = $usuario->login;
        $_SESSION['ang_plataforma_email'] = $usuario->email;
        $_SESSION['ang_plataforma_estabelecimento'] = $usuario->idestabelecimento;
        $_SESSION['ang_plataforma_perfil'] = $usuario->perfil;
        $_SESSION['ang_plataforma_plano'] = $usuario->plano;

        http_response_code(200);
        $response = $usuario;
    } else {
        $response->status = 'error';
        $response->message = 'Favor verifique os dados, credenciais informada está incorreta.';
    }

    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}
