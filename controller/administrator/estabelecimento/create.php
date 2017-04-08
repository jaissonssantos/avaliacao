<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$estabelecimento = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset(
        //$estabelecimento->hash,
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
        $estabelecimento->idplano
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }
    
    // Gerar url (hash) do estabelecimento
    $estabelecimento->hash = friendlyURL($estabelecimento->nomefantasia);
    $loops = 0;
    $findHash =  true;
    while($findHash){
        $stmt = $oConexao->prepare('SELECT hash FROM estabelecimento WHERE hash = ? LIMIT 1');
        $stmt->execute(array($estabelecimento->hash));
        $findHash = $stmt->fetchObject();
        if($findHash) $estabelecimento->hash = $estabelecimento->hash . '-'. $loops . rand(0,9999);
        if($loops > 20) {
            throw new Exception('Tente usar um nome fantasia diferente', 500);
        }
        $loops++;
    }

    $stmt = $oConexao->prepare('INSERT INTO estabelecimento(hash,cnpjcpf,razaosocial,nomefantasia,sobre,idsegmento,email,telefonecomercial,cep,logradouro,numero,complemento,bairro,idestado,idcidade,idplano,datacadastro,licencainicial) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,now(),now())');
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
        $estabelecimento->idplano,
    ));

    http_response_code(200);
    $response->success = 'Cadastrado sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
}

echo json_encode($response);