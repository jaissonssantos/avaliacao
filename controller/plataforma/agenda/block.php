<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    if (!isset($params->profissional)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $hash = generatehash();
    $idusuario = $_SESSION['ang_plataforma_uid'];
    $idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'INSERT INTO 
        agendamento( 
            hash,idprofissional,horainicial,horafinal,status,idusuario,idestabelecimento,datacadastro
        ) VALUES(?,?,?,?,6,?,?,now())'
    );
    $stmt->execute(array(
        $hash,
        $params->profissional,
        $params->dtainicio,
        $params->dtafim,
        $idusuario,
        $idestabelecimento
    ));

    $results = array(
        'id' => $hash,
        'dtainicio' => $params->dtainicio,
        'dtafim' =>  $params->dtafim,
        'idprofissional' => $params->profissional,
        'status' => 6
    );

    $oConexao->commit();
    http_response_code(200);

    $response = array(
        'results' => $results,
        'success' => 'Pronto! horários bloqueados com sucesso',
    );

} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
