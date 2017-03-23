<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->professional) {
        $stmt = $oConexao->prepare('SELECT pd.dia, pd.horainicial, pd.horafinal
										FROM profissional_diastrabalho pd
										WHERE pd.idprofissional = :id');
        $stmt->bindValue('id', $params->professional->id); //idprofissional
        $stmt->execute();
        $profissionaldiatrabalho = $stmt->fetchAll(PDO::FETCH_OBJ);

        if ($profissionaldiatrabalho) {
            echo json_encode($profissionaldiatrabalho);
            $oConexao = null;
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
