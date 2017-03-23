<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->hash)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT
			e.id,e.hash,e.cnpjcpf,e.razaosocial,e.nomefantasia,e.sobre,e.email,e.telefonecomercial,e.cep,e.logradouro,e.numero,e.complemento,e.bairro,e.licencainicial,e.licencafinal,e.datacadastro,
			pl.nome,pl.valormensal,pl.valorsemestral,pl.valoranual,e.imagem
			es.nome as estado,ci.nome as cidade
		 FROM estabelecimento e
		 INNER JOIN plano pl ON (e.idplano = pl.id)
		 INNER JOIN estado es ON (e.idestado = es.idestado)
		 INNER JOIN cidade ci ON (e.idcidade = ci.idcidade)
		 WHERE e.hash = :hash
		 LIMIT 1'
    );
    $stmt->bindParam('hash', $params->hash);
    $stmt->execute();

    http_response_code(200);
    $estabelecimento = $stmt->fetchObject();
    if (!$estabelecimento) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }
    $response = $estabelecimento;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
