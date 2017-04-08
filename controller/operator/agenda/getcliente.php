<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->id > 0) {
        $stmt = $oConexao->prepare('SELECT * FROM cliente WHERE id = :id');
        $stmt->bindValue('id', $params->id); //hash do estabelecimento
        $stmt->execute();
        $cliente = $stmt->fetchAll(PDO::FETCH_OBJ);

        if ($cliente) {
            echo json_encode($cliente);
            $oConexao = null;
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
