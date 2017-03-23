<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (isset($params->estabelecimento)) {
        $estabelecimento = $params->estabelecimento;
    }

    if (!isset(
        $estabelecimento->hash,
        $estabelecimento->cnpjcpf,
        $estabelecimento->razaosocial,
        $estabelecimento->nomefantasia,
        $estabelecimento->sobre,
        $estabelecimento->idsegmento,
        $estabelecimento->email,
        $estabelecimento->telefonecomercial,
        $estabelecimento->cep,
        $estabelecimento->logradouro,
        $estabelecimento->numero,
        $estabelecimento->complemento,
        $estabelecimento->bairro,
        $estabelecimento->idestado,
        $estabelecimento->idcidade,
        $estabelecimento->licencainicial,
        $estabelecimento->licencafinal,
        $estabelecimento->idplano
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare('UPDATE estabelecimento SET hash=?,cnpjcpf=?,razaosocial=?,nomefantasia=?,sobre=?,idsegmento=?,email=?,telefonecomercial=?,cep=?,logradouro=?,numero=?,complemento=?,bairro=?,idestado=?,idcidade=?,licencainicial=?,licencafinal=?,idplano=?) WHERE id=?');
    $stmt->execute(array(
        $estabelecimento->hash,
        $estabelecimento->cnpjcpf,
        $estabelecimento->razaosocial,
        $estabelecimento->nomefantasia,
        $estabelecimento->sobre,
        $estabelecimento->idsegmento,
        $estabelecimento->email,
        $estabelecimento->telefonecomercial,
        $estabelecimento->cep,
        $estabelecimento->logradouro,
        $estabelecimento->numero,
        $estabelecimento->complemento,
        $estabelecimento->bairro,
        $estabelecimento->idestado,
        $estabelecimento->idcidade,
        $estabelecimento->licencainicial,
        $estabelecimento->licencafinal,
        $estabelecimento->idplano,
        $estabelecimento->id,
    ));

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
    $oConexao->rollBack();
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
