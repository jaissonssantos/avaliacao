<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    $uid = isset($_SESSION['ang_markday_uid']) ? $_SESSION['ang_markday_uid'] : null;
    $stmt = $oConexao->prepare(
        'SELECT id,nome,cpf,email,telefonecelular celular,imagem
        FROM cliente
        WHERE id=? LIMIT 1');

    $stmt->execute(array($uid));
    $cliente = $stmt->fetchObject();
    if (!$cliente) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }

    $cliente->imagem = STORAGE_URL .'/cliente/' . $cliente->imagem;

    http_response_code(200);
    $response = array(
        'results' => $cliente
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