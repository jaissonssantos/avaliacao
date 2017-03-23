<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->login) {
        $stmt = $oConexao->prepare('SELECT id, nome, login FROM cliente WHERE upper(login) = upper(:login)');
        $stmt->bindParam('login', $params->login);
        $stmt->execute();
        $clientelogin = $stmt->fetchObject();
        $oConexao = null;

        if ($clientelogin) {
            echo '{ "cliente": "exists" }';
        } else {
            echo '{ "cliente": "notexists" }';
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
