<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//get session local browser
$uid = $_SESSION['ang_markday_uid'];

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($uid) {

        //verification cpf
        $stmt = $oConexao->prepare('SELECT count(id) as total
      									FROM cliente
      										WHERE 
      										id <> :id
      										AND
      										telefonecelular = :phone');
        $stmt->bindValue('id', $uid);
        $stmt->bindValue('phone', $params->phone);
        $stmt->execute();
        $total = $stmt->fetchObject();

        if ($total->total > 0) {
            echo '{ "status": "error",  "message": "Ops! Telefone já veiculado há outro cliente, favor informe outro" }';
        } else {
            echo '{ "status": "success",  "message": "ok" }';
        }
    } else {
        echo '{ "status": "error",  "message": "Ops! faça o login novamente para atualizar seu perfil" }';
    }

    //close connection
    $oConexao = null;
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
