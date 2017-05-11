<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $idestabelecimento = $_SESSION['avaliacao_estabelecimento'];

    $count_cli = $oConexao->prepare(
        'SELECT COUNT(*) FROM cliente c, estabelecimento_cliente ec where c.id = ec.idcliente AND ec.idestabelecimento = :id AND WEEK(c.created_at) = WEEK(NOW())'
    );
    $count_cli->bindParam('id', $idestabelecimento);
    $count_cli->execute();
    $count_results_cli = $count_cli->fetchColumn();

    $count_ques = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM questionario  WHERE WEEK(created_at) = WEEK(NOW()) AND idestabelecimento = :id'
    );
    $count_ques->bindParam('id', $idestabelecimento);
    $count_ques->execute();
    $count_results_ques = $count_ques->fetchColumn();


    http_response_code(200);
    $response = array(
        'count' => array(
            'clientes' => $count_results_cli,
            'questionarios' => $count_results_ques
        ),
    );
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
