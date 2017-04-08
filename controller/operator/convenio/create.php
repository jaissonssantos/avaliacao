<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $convenio = (array) $params;

    $required = array(
        'nome',
    );

    $convenio = array_intersect_key($convenio, array_flip($required));

    if (count($convenio) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Default Parameters
    $convenio['status'] = 1;
    $convenio['idestabelecimento'] = $_SESSION['ang_plataforma_estabelecimento'];

    // Inserir um novo convenio
    $stmt = $oConexao->prepare(
        'INSERT INTO
			convenio(
				nome,idestabelecimento,status
		) VALUES (
			:nome,:idestabelecimento,:status
		)
		'
    );

    $stmt->execute($convenio);

    http_response_code(200);
    $response->success = 'Cadastrado com sucesso';
    $response->id = $oConexao->lastInsertId();
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
