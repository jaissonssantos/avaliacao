<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    if (!isset($params->estado)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare('SELECT id,nome FROM cidade WHERE idestado = ?');
    $stmt->execute(array(
        $params->estado
    ));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!$results){
        throw new Exception('Nenhum resultado encontrado', 404);
    } 
    
    http_response_code(200);
    $response = array(
        'results' => $results
    );

} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
