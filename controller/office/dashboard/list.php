<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $idestabelecimento = $_SESSION['avaliacao_estabelecimento'];

    //echo $idestabelecimento;

    $count_est = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM pergunta p, questionario q where p.idquestionario = q.id AND q.idestabelecimento = :id'
    );
    $count_est->bindParam('id', $idestabelecimento);

    $count_est->execute();
    $count_results_est = $count_est->fetchColumn();

    $count_cli = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM cliente c, estabelecimento_cliente ec  where c.id = ec.idcliente AND ec.idestabelecimento = :id'
    );
    $count_cli->bindParam('id', $idestabelecimento);
    $count_cli->execute();
    $count_results_cli = $count_cli->fetchColumn();

    $count_ques = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM questionario  where  idestabelecimento = :id'
    );
    $count_ques->bindParam('id', $idestabelecimento);
    $count_ques->execute();
    $count_results_ques = $count_ques->fetchColumn();

    $count_user = $oConexao->prepare(
        'SELECT
            COUNT(*)
        FROM usuario  where idestabelecimento = :id'
    );
    $count_user->bindParam('id', $idestabelecimento);
    $count_user->execute();
    $count_results_user = $count_user->fetchColumn();


    http_response_code(200);
    $response = array(
        'count' => array(
            'perguntas' => $count_results_est,
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
