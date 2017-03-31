<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if (isset($params->estado)) {
        $stmt = $oConexao->prepare('SELECT id,nome FROM cidade WHERE idestado = :estado');
        $stmt->bindParam('estado', $params->estado);
        $stmt->execute();
        $cidade = $stmt->fetchAll(PDO::FETCH_OBJ);
        $oConexao = null;
        if ($cidade) {
            echo json_encode($cidade);
        } else {
            echo '{ "error": "true" }';
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
