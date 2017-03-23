<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

try {
    $stmt = $oConexao->prepare('SELECT nome, hash
									FROM segmento ORDER BY nome ASC');
    $stmt->execute();
    $segmento = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($segmento) {
        echo json_encode($segmento);
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
