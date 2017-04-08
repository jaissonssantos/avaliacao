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
        'interno',
        'valorpororcamento',
        'valor',
        'promocao',
        'idusuario',
        'idservico_categoria',
        'duracao'
    );

    $servico = array_intersect_key($servico, array_flip($required));

    if (count($servico) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    if ($servico['promocao'] && !isset($params->valorpromocao)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }
    $servico['interno'] = isset($params->interno) && $params->interno
                                                                ? 1
                                                                : 0;
    $servico['promocao'] = isset($params->promocao) && $params->promocao 
                                                                ? 1
                                                                : 0;
    $servico['valorpromocao'] = isset($params->valorpromocao) && !$params->valorpororcamento
                                                                ? $params->valorpromocao
                                                                : null;
    $servico['valor'] = isset($params->valor) && !$params->valorpororcamento
                                                                ? $params->valor
                                                                : null;

    $stmt = $oConexao->prepare(
            'UPDATE servico
				SET nome=:nome,descricao=:descricao,interno=:interno,valorpororcamento=:valorpororcamento,valor=:valor,promocao=:promocao,
						valorpromocao=:valorpromocao,idusuario=:idusuario,duracao=:duracao,
						idservico_categoria=:idservico_categoria
			WHERE id=:id'
    );
    $stmt->execute($servico);

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
