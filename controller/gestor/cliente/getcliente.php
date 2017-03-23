<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->id > 0) {
        $stmt = $oConexao->prepare('SELECT idusuario, nome, email, perfil FROM usuario WHERE idusuario = :id');
        $stmt->bindParam('id', $params->id);
        $stmt->execute();
        $usuario = $stmt->fetchObject();
        $oConexao = null;
        echo json_encode($usuario);
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
