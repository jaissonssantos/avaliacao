<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//get session local browser
$uid = $_SESSION['ang_markday_uid'];
$cpf = $_SESSION['ang_markday_cpf'];
$email = $_SESSION['ang_markday_email'];

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params->hashscheduled) {
        $stmt = $oConexao->prepare('SELECT hash, horainicial, horafinal
										FROM agendamento 
										WHERE
											hash = :hash
											AND
											status <= 3
											AND 
											idcliente = :uid');
        $stmt->bindValue('hash', $params->hashscheduled); //hash do estabelecimento
        $stmt->bindValue('uid', $uid);
        $stmt->execute();
        $agendamento = $stmt->fetchAll(PDO::FETCH_OBJ);

        if ($agendamento) {
            echo json_encode($agendamento);
        }

        //close connection
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
