<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $count_est = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM estabelecimento'
    );
    $count_est->execute();
    $count_results_est = $count_est->fetchColumn();

    $count_cli = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM cliente'
    );
    $count_cli->execute();
    $count_results_cli = $count_cli->fetchColumn();

    $count_ques = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM questionario'
    );
    $count_ques->execute();
    $count_results_ques = $count_ques->fetchColumn();

    $count_user = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM usuario'
    );
    $count_user->execute();
    $count_results_user = $count_user->fetchColumn();


    http_response_code(200);
    $response = array(
        'count' => array(
            'estabelecimentos' => $count_results_est,
            'clientes' => $count_results_cli,
            'usuarios' => $count_results_user,
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
