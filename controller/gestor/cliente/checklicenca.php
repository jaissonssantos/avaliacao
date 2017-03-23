<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->licenca) {
        $stmt = $oConexao->prepare("SELECT 
										cc.codigo_licenca, cc.plano_id, 
										cc.cliente_id, s.nome, s.tipo 
									FROM 
									cliente_contrato cc 
									INNER JOIN 
									status s 
									ON ( cc.status_id = s.id )
									WHERE 
									s.tipo = 'contrato' 
									AND 
									s.nome = 'Ativo' 
									AND 
									cc.codigo_licenca = :licenca AND cc.data_fim <= now()");
        $stmt->bindParam('licenca', $params->licenca);
        $stmt->execute();
        $licenca = $stmt->fetchObject();
        $oConexao = null;

        if ($licenca) {
            echo '{ "licenca": "true" }';
        } else {
            echo '{ "licenca": "false" }';
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
