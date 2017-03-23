<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();

//get session local browser
$uid = $_SESSION['ang_markday_uid'];

//params
$params = json_decode(file_get_contents('php://input'));

try {
    if ($uid) {
        $stmt = $oConexao->prepare('SELECT count(id) as total FROM cliente WHERE id = :id AND senha <> :passwordold');
        $stmt->bindValue('id', $uid);
        $stmt->bindValue('passwordold', sha1(SALT.$params->passwordold));
        $stmt->execute();
        $item = $stmt->fetchObject();

        if ($item->total >= 1) {
            echo '{ "status": "error",  "message": "Ops! achamos que você informou a senha atual errada, favor verifique" }';
        } else {
            $stmt = $oConexao->prepare('UPDATE cliente SET senha = :passwordnew WHERE id = :id');
            $stmt->bindValue('passwordnew', sha1(SALT.$params->passwordnew));
            $stmt->bindValue('id', $uid);
            $cliente = $stmt->execute();

            if ($cliente) {
                echo '{ "status": "success",  "message": "senha atualizado com sucesso" }';
            } else {
                echo '{ "status": "error",  "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
            }
        }

        //close connection
        $oConexao = null;
    } else {
        echo '{ "status": "error",  "message": "Ops! faça o login novamente para atualizar seu perfil" }';
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
