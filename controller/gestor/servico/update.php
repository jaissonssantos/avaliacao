<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $servico = (array) $params;

    $required = array(
        'id',
        'nome',
        'descricao',
        'valor',
        'promocao',
        'idusuario',
        'idservico_categoria',
    );

    $servico = array_intersect_key($servico, array_flip($required));

    if (count($servico) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    if ($servico['promocao'] && !isset($params->valorpromocao)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }
    $servico['valorpromocao'] = isset($params->valorpromocao)
                                                                ? $params->valorpromocao
                                                                : 0;

    $stmt = $oConexao->prepare(
            'UPDATE servico
				SET nome=:nome,descricao=:descricao,valor=:valor,promocao=:promocao,
						valorpromocao=:valorpromocao,idusuario=:idusuario,
						idservico_categoria=:idservico_categoria
			WHERE id=:id'
    );
    $stmt->execute($servico);

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
