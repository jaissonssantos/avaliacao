<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
sleep(3);
//params json
$params = json_decode(file_get_contents('php://input'));

//return JSON
$cp = array();

try {
    $search_services = "%{$params->services}%";

    $stmt = $oConexao->prepare('SELECT DISTINCT a.hash, a.nomefantasia
									FROM estabelecimento a
										WHERE 
											a.nomefantasia LIKE :services
										ORDER BY a.idplano DESC');
    $stmt->bindValue('services', $search_services, PDO::PARAM_STR);
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $i = 0;
    if ($companies) {
        foreach ($companies as $row) {
            $cp['results'][$i] = $row['nomefantasia'];

            ++$i;
        }
    } else {
        $stmt = $oConexao->prepare('SELECT DISTINCT a.nome, a.hash
									FROM servico a
										WHERE 
											a.nome LIKE :services
										ORDER BY a.nome ASC');
        $stmt->bindValue('services', $search_services, PDO::PARAM_STR);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($services) {
            foreach ($services as $row) {
                $cp['results'][$i] = $row['nome'];

                ++$i;
            }
        }
    }

    echo json_encode($cp);
    $oConexao = null;
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
