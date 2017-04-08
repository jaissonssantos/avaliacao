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

    $param = array();
    $param['id'] = $params->id;
    $param['idestabelecimento'] = $_SESSION['ang_plataforma_estabelecimento'];

    $stmt = $oConexao->prepare(
        'SELECT
					cl.id,cl.nome,cl.imagem,DATE_FORMAT(cl.datanascimento, "%d/%m/%Y") as datanascimento,cl.cpf,cl.email,
					cl.telefonecelular,cl.telefonecomercial,cl.cep,cl.logradouro,
					cl.numero,cl.complemento,cl.bairro,cl.idcidade,cl.idestado
				FROM cliente cl
                LEFT JOIN cliente_estabelecimento ce ON(cl.id = ce.idcliente)
				WHERE cl.id=:id
                    AND
                    ce.idestabelecimento=:idestabelecimento
        LIMIT 1'
    );

    $stmt->execute($param);
    $cliente = $stmt->fetchObject();

    if (!$cliente) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $cliente;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
