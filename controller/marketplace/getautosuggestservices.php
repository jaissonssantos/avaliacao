<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//return JSON
$servicescategory = array();

try {
    $stmt = $oConexao->prepare('SELECT distinct nome, id
										FROM servico_categoria LIMIT 0,15');
    $stmt->execute();
    $servicescategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $i = 0;
    if ($servicescategory) {
        foreach ($servicescategory as $row) {
            $servicescategory[$i]['id'] = $row['id'];
            $servicescategory[$i]['nome'] = $row['nome'];

            $stmtservice = $oConexao->prepare('SELECT distinct a.nome, a.hash
														FROM servico a
														WHERE
															a.idservico_categoria = :services LIMIT 0,8');
            $stmtservice->bindValue('services', $row['id']); //id servico categoria
                $stmtservice->execute();
            $sc = $stmtservice->fetchAll(PDO::FETCH_ASSOC);
            if ($sc) {
                foreach ($sc as $l) {
                    $item['hash'] = $l['hash'];
                    $item['nome'] = $l['nome'];

                        //add item in data
                        $servicescategory[$i]['item'][] = $item;
                }
            }

            ++$i;
        }

        echo json_encode($servicescategory);
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
