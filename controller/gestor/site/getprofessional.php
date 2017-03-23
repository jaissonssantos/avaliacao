<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

try {
    $stmt = $oConexao->prepare('SELECT id, nome
									FROM profissional p
									WHERE p.liberado = 1 AND p.idestabelecimento = :id ORDER BY p.nome ASC');
    $stmt->bindValue('id', $_SESSION['ang_plataforma_estabelecimento']); //idestabelecimento
    $stmt->execute();
    $profissional = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($profissional);
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
