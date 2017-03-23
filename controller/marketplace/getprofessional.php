<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

try {
    if ($params) {
        $stmt = $oConexao->prepare('SELECT p.id, p.nome, p.profissao, p.email, p.tempoconsulta, p.foto
										FROM profissional p
										INNER JOIN profissional_servico ps ON(p.id = ps.idprofissional)
										INNER JOIN estabelecimento e ON(e.id = p.idestabelecimento)
										LEFT JOIN servico s ON(s.id = ps.idservico)
											WHERE p.status = 1 AND e.hash = :establishment AND s.hash = :service ORDER BY p.nome ASC');
        $stmt->bindValue('establishment', strip_tags($params->establishment)); //idestabelecimento
        $stmt->bindValue('service', strip_tags($params->service)); //idservico
        $profissional = $stmt->execute();

        if ($profissional) {
            // $p = $stmt->fetchAll(PDO::FETCH_OBJ);
            while ($p = $stmt->fetch(PDO::FETCH_OBJ)) {
                $msg[] = (array) $p;
            }
            echo json_encode($msg);
            $oConexao = null;
        }
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
