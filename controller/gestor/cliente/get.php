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

    $id = $params->id;

    $stmt = $oConexao->prepare(
        'SELECT
					cl.id,cl.nome,cl.imagem,cl.datanascimento,cl.cpf,cl.email,
					cl.telefonecelular,cl.telefonecomercial,cl.cep,cl.logradouro,
					cl.numero,cl.complemento,cl.bairro,cl.idcidade,cl.idestado
				FROM cliente cl
				WHERE cl.id = ?
        LIMIT 1'
    );

    $stmt->execute(array($id));
    $cliente = $stmt->fetchObject();

    if (!$cliente) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $cliente;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    echo $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
