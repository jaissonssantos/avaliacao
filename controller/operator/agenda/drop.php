<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    if (!isset($params->id)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $idusuario = $_SESSION['ang_plataforma_uid'];
    $idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'UPDATE agendamento SET 
          idprofissional=?,horainicial=?,horafinal=?,idusuario=?,idestabelecimento=?
        WHERE id=?'
    );
    $stmt->execute(array(
        $params->profissional,
        $params->dtainicio,
        $params->dtafim,
        $idusuario,
        $idestabelecimento,
        $params->id
    ));

    $oConexao->commit();
    http_response_code(200);
    $response->success = 'Pronto! Seu agendamento atualizado com sucesso';

} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
