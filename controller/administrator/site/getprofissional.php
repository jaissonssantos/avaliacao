<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

try {
    $stmt = $oConexao->prepare('SELECT a.ativo, a.idprofissional FROM page_profissional a
									LEFT JOIN estabelecimento_page b ON(a.idestabelecimento_page = b.id)
									WHERE 
										b.idestabelecimento = :id  
										AND 
										a.ativo = 1');
    $stmt->bindValue('id', $_SESSION['ang_plataforma_estabelecimento']); //idestabelecimento
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
