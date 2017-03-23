<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));
try {
    if ($params->date) {
        $date = date_create($params->date);
        $datemarket = date_format($date, 'Y-m-d');
        $datemarketinit = $datemarket.' 00:00:00';
        $datemarketfinal = $datemarket.' 23:59:59';

        /*status(1 - agendado, 2 - agendado online, 3 confirmado)*/
        $stmt = $oConexao->prepare('SELECT a.id, a.horainicial, a.horafinal
										FROM agendamento a
									INNER JOIN estabelecimento e ON(e.id = a.idestabelecimento) 
									WHERE 
										status <= 3 AND 
										e.hash = :hash AND 
										a.idprofissional = :professional AND 
										a.horainicial >= :datainicial AND
										a.horainicial <= :datafinal ');
        $stmt->bindValue('hash', $params->hash); //idestabelecimento
        $stmt->bindValue('professional', $params->professional); //idprofissional
        $stmt->bindValue('datainicial', $datemarketinit); //data inicial
        $stmt->bindValue('datafinal', $datemarketfinal); //data final
        $marked = $stmt->execute();

        if ($marked) {
            $m = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($m);
            $oConexao = null;
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
