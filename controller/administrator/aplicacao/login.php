<?php

use Utils\Conexao;
sleep(1);
header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    if (!isset(
        $params->email,
        $params->password
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT id,nome,login,email,gestor
		FROM usuario
		WHERE 
            email=upper(?) 
		AND 
			senha=?
		AND
			status=1
        AND
            gestor=1'
    );
    $stmt->execute(array(
        $params->email,
        sha1(SALT.$params->password)
    ));
    $results = $stmt->fetchObject();

    if($results){

        $stmt = $oConexao->prepare(
            'SELECT roles as item
            FROM usuario_permissao
            WHERE 
                idusuario=?'
        );
        
        $stmt->execute(array(
            $results->id
        ));
        $results->roles = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $oConexao->prepare(
            'UPDATE usuario 
				SET datalogin=now()
			WHERE id=:id'
        );
        $stmt->execute(array('id' => $results->id));

        $_SESSION['ang_gestor_uid'] = $results->id;
        $_SESSION['ang_gestor_name'] = $results->nome;
        $_SESSION['ang_gestor_login'] = $results->login;
        $_SESSION['ang_gestor_email'] = $results->email;
        $_SESSION['ang_gestor_roles'] = $results->roles;
    } 
    http_response_code(200);
    if (!$results) {
        throw new Exception('Favor verifique os dados, credenciais informada está incorreta', 404);
    }
    $response = array(
        'results' => $results
    );

} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
