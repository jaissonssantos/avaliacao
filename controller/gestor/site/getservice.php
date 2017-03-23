<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    $stmt = $oConexao->prepare('SELECT a.icone, a.ativo, a.idservico FROM page_servico a
									LEFT JOIN estabelecimento_page b ON(a.idestabelecimento_page = b.id)
									WHERE 
										b.idestabelecimento = :id  
										AND 
										a.ativo = 1');
    $stmt->bindValue('id', $_SESSION['ang_plataforma_estabelecimento']); //idprofissional
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($services) {
        echo json_encode($services);
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
