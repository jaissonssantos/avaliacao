<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset(
        $params->nome,
        $params->email, 
        $params->telefone,
        $params->senha
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $oConexao->beginTransaction();

    //Default params
    $params->senha = sha1(SALT.$params->senha);

    $stmt = $oConexao->prepare('INSERT INTO
                 cliente(nome,email,telefone,senha,created_at,updated_at
                ) VALUES (?,?,?,?,now(),now())');
    $stmt->execute(array(
        $params->nome,
        $params->email, 
        $params->telefone,
        $params->senha
    ));    
    $cliente_id = $oConexao->lastInsertId();

    $_SESSION['avaliacao_cliente_uid'] = $cliente_id;
    $_SESSION['avaliacao_cliente_nome'] = $params->nome;
    $_SESSION['avaliacao_cliente_email'] = $params->email;

    $oConexao->commit();

    http_response_code(200);
    $response->success = 'Cadastrado sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    // $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
