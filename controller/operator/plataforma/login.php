<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->email)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare('SELECT a.id,a.nome,a.login,a.email,a.idestabelecimento,
        a.perfil, c.nome as plano
	    FROM usuario a
		LEFT JOIN estabelecimento b ON (a.idestabelecimento = b.id)
		LEFT JOIN plano c ON (b.idplano = c.id)
		WHERE (upper(a.email)=upper(:email)  OR upper(a.login)=upper(:login))
        AND a.senha=:senha 
        AND a.status=1
        LIMIT 1'
    );
    $stmt->bindValue('login', $params->email);
    $stmt->bindValue('email', $params->email);
    $stmt->bindValue('senha', sha1(SALT.$params->password));
    $stmt->execute();
    $results = $stmt->fetchObject();

    if (!$results) {
        throw new Exception('Favor verifique os dados, credenciais informada está incorreta', 404);
    }
    //create session local browser
    $_SESSION['ang_plataforma_uid'] = $results->id;
    $_SESSION['ang_plataforma_nome'] = $results->nome;
    $_SESSION['ang_plataforma_login'] = $results->login;
    $_SESSION['ang_plataforma_email'] = $results->email;
    $_SESSION['ang_plataforma_estabelecimento'] = $results->idestabelecimento;
    $_SESSION['ang_plataforma_perfil'] = $results->perfil;
    $_SESSION['ang_plataforma_plano'] = $results->plano;

    http_response_code(200);
    $response = $results;

} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);