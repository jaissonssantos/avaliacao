<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

try {
    $stmt = $oConexao->prepare('SELECT tipo, url
									FROM estabelecimento_redesocial p
									WHERE p.idestabelecimento = :id');
    $stmt->bindValue('id', $_SESSION['ang_plataforma_estabelecimento']); //idestabelecimento
    $stmt->execute();
    $profissional = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($profissional);
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
