<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));
$msg = array();
$iduser = $_SESSION['ang_plataforma_uid'];

try {
    $stmt = $oConexao->prepare('UPDATE usuario SET senha = :senha WHERE id = :id');
    $stmt->bindValue('senha', sha1(SALT.$params->senha));
    $stmt->bindValue('id', $iduser);
        //;
// 		$usuario = $stmt->fetchObject();

        if ($stmt->execute()) {
            $msg['msg'] = 'success';
            echo json_encode($msg);
        } else {
            $msg['msg'] = 'error';
            echo json_encode($msg);
        }
} catch (PDOException $e) {
    $oConexao->rollBack();
    $msg['msg'] = 'error';
    $msg['error'] = $e->getMessage();
    echo json_encode($msg);
    die();
}
