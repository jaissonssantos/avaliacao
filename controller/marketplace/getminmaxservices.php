<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

try {
    $stmt = $oConexao->prepare('SELECT min(valor) as valorminimo, max(valor) as valormaximo
									FROM servico');
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($services) {
        echo json_encode($services);
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
