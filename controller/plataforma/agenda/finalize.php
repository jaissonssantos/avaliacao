<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->id)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    if (!isset($params->valor)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    //Default parameters
    $hash = generatehash();

    $oConexao->beginTransaction();

    // Atualiza status(4 - atendido ou finalizado) e forma de pagamento do agendamento
    $stmt = $oConexao->prepare(
        'UPDATE agendamento 
			SET status=4,idformapagamento=:idformapagamento 
		WHERE id=:id');
    $stmt->execute(array('idformapagamento' => $params->idformapagamento, 'id' => $params->id));

    //Inserir o pagamento
    $stmt = $oConexao->prepare(
        'INSERT INTO 
    		agendamento_pagamento(
    			idagendamento,codigo,valor,valorconvenio,datacadastro
    		)
    		VALUES(
    			:id,:hash,:valor,:valorconvenio,now()
    		)'
    );
    $stmt->execute(array('id' => $params->id, 'hash' => $hash, 'valor' => $params->valor, 'valorconvenio' => $params->valorconvenio));
    $agendamento_pagamento_item = array('idagendamento_pagamento' => $oConexao->lastInsertId());

    //Inserir item do agendamento
    $stmt = $oConexao->prepare(
        'INSERT INTO 
			agendamento_pagamento_item(
				idagendamento_pagamento,idservico,valor
			) VALUES (
				:idagendamento_pagamento,:idservico,:valor
			)'
    );
    $servicos = $params->servicos;
    foreach ($servicos as $servico) {
        if (isset($servico->id)) {
            $agendamento_pagamento_item['idservico'] = $servico->id;
            if (isset($servico->valorpgto) && $servico->valorpororcamento == 1) {
                $agendamento_pagamento_item['valor'] = $servico->valorpgto;
            } else {
                if ($servico->valorpororcamento == 0) {
                    if ($servico->promocao == 0) {
                        $agendamento_pagamento_item['valor'] = $servico->valor;
                    } elseif ($servico->promocao == 1) {
                        $agendamento_pagamento_item['valor'] = $servico->valorpromocao;
                    }
                }
            }
            $stmt->execute($agendamento_pagamento_item);
        }
    }

    $oConexao->commit();
    http_response_code(200);
    $response->success = 'Eba! Agendamento está finalizado';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
