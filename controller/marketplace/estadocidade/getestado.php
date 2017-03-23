<?php
use Utils\Conexao;

$oConexao = Conexao::getInstance();

try {
    $stmt = $oConexao->prepare('SELECT * FROM estado');
    $stmt->execute();
    $estado = $stmt->fetchAll(PDO::FETCH_OBJ);
    $oConexao = null;
    if ($estado) {
        echo json_encode($estado);
    } else {
        echo '{ "error": "true" }';
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
