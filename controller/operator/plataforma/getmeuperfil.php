<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
//params json
$iduser = $_SESSION['ang_plataforma_uid'];

try {
    $stmt = $oConexao->prepare('SELECT * FROM usuario WHERE id = :id');
    $stmt->bindValue('id', $iduser); //hash do estabelecimento
        $stmt->execute();
    $profissional = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($profissional) {
        echo json_encode($profissional);
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
