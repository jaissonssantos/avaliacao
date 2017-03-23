<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//get session local browser
$uid = $_SESSION['ang_markday_uid'];
$cpf = $_SESSION['ang_markday_cpf'];
$email = $_SESSION['ang_markday_email'];

try {
    $stmt = $oConexao->prepare('SELECT id, nome as name, cpf, email, telefonecelular as phone, imagem as thumbnail
									FROM cliente
										WHERE
											id = :id');
    $stmt->bindValue('id', $uid);
    $stmt->execute();
    $cliente = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($cliente) {
        echo json_encode($cliente);
    } else {
        echo '{ "status": "error",  "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
    }

    //close connection
    $oConexao = null;
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
