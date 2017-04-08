<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->id)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $stmt = $oConexao->prepare(
        'SELECT
			e.id,e.hash,e.cnpjcpf,e.razaosocial,e.nomefantasia,e.imagem,e.sobre,e.email,e.telefonecomercial,e.cep,e.logradouro,e.numero,e.complemento,e.bairro,e.idsegmento,e.idplano,e.idestado,e.idcidade,
            DATE_FORMAT(e.licencainicial, "%d/%m/%Y") licencainicial,
            DATE_FORMAT(e.licencafinal, "%d/%m/%Y") licencafinal,
            e.datacadastro,
			pl.nome,pl.mensal,pl.anual,
			es.nome as estado,ci.nome as cidade
		 FROM estabelecimento e
		 LEFT JOIN plano pl ON (e.idplano = pl.id)
		 INNER JOIN estado es ON (e.idestado = es.idestado)
		 INNER JOIN cidade ci ON (e.idcidade = ci.idcidade)
		 WHERE e.id = :id
		 LIMIT 1'
    );
    $stmt->bindParam('id', $params->id);
    $stmt->execute();

    http_response_code(200);
    $estabelecimento = $stmt->fetchObject();
    if (!$estabelecimento) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }
    $estabelecimento->imagem = STORAGE_URL . '/estabelecimento/' . $estabelecimento->imagem;
    $response = $estabelecimento;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
