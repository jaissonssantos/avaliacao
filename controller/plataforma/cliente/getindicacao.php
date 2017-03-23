<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->indicacao) {
        $stmt = $oConexao->prepare('SELECT id, nome, login FROM cliente WHERE upper(login) = upper(:indicacao)');
        $stmt->bindParam('indicacao', $params->indicacao);
        $stmt->execute();
        $clienteindicacao = $stmt->fetchObject();
        $oConexao = null;

        if ($clienteindicacao) {
            echo json_encode($clienteindicacao);
        } else {
            echo '{ "indication": "null" }';
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
