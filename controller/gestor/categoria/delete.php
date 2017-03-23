<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

//get session local browser
//$uid = $_SESSION['ang_ktcomentario_uid'];
$response = new stdClass();

try {

    //apagar o comentario
    $stmt = $oConexao->prepare('DELETE FROM servico_categoria WHERE id=:id');
    $stmt->bindValue(':id', $params->id);
    if ($stmt->execute()) {
        http_response_code(200);
        $response->success = 'Coordenador excluído com sucesso';
    } else {
        http_response_code(500);
        $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    }
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
