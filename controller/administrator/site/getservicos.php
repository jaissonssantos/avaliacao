<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

try {
    $stmt = $oConexao->prepare('SELECT * FROM servico s WHERE s.idestabelecimento = :id order by s.nome');
    $stmt->bindValue('id', $_SESSION['ang_plataforma_estabelecimento']); //idestabelecimento
    $stmt->execute();
    $servicos = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($servicos);
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
