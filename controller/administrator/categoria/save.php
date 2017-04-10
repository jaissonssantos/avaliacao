<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

//get session local browser
//$uid = $_SESSION['ang_ktcomentario_uid'];
$response = new stdClass();

try {

    //inserir o comentario
    $stmt = $oConexao->prepare('INSERT INTO servico_categoria(nome) VALUES(:nome)');
    $stmt->bindValue('nome', $params->nome);
    $stmt->execute();

    http_response_code(200);
    $response->success = 'Categoria salva com sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);