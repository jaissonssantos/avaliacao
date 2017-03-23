<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params json
$params = json_decode(file_get_contents('php://input'));

//return JSON
$service = array();

try {
    if ($params->establishment != '' && $params->service != '') {
        $stmt = $oConexao->prepare('SELECT a.nome, a.descricao, a.valorpororcamento, a.valor, a.promocao, a.valorpromocao,
										b.nomefantasia, b.cep, b.logradouro, b.numero, b.complemento, b.bairro,
										c.campomsgpgtoobrigatorio, c.campomsgpgtodescricao,
										d.sigla as estado, e.nome as cidade
										FROM servico a
										LEFT JOIN estabelecimento b ON(a.idestabelecimento = b.id) 
										LEFT JOIN estabelecimento_configuracao c ON(a.idestabelecimento = c.idestabelecimento) 
										LEFT JOIN estado d ON(b.idestado = d.idestado)
										LEFT JOIN cidade e ON(b.idcidade = e.idcidade)
											WHERE 
												a.hash = :service
												AND
												b.hash = :establishment');
        $stmt->bindValue('service', $params->service); //hash do estabelecimento
        $stmt->bindValue('establishment', $params->establishment); //hash do serviço
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $i = 0;
        if ($services) {
            foreach ($services as $row) {
                $service[$i]['nome'] = $row['nome'];
                $service[$i]['descricao'] = $row['descricao'];
                $service[$i]['valorpororcamento'] = $row['valorpororcamento'] == 1 ? true : false;
                $service[$i]['valor'] = $row['valor'];
                $service[$i]['promocao'] = $row['promocao'];
                $service[$i]['valorpromocao'] = $row['valorpromocao'];
                $service[$i]['nomefantasia'] = $row['nomefantasia'];
                $service[$i]['cep'] = $row['cep'];
                $service[$i]['logradouro'] = $row['logradouro'];
                $service[$i]['numero'] = $row['numero'];
                $service[$i]['complemento'] = $row['complemento'];
                $service[$i]['bairro'] = $row['bairro'];
                $service[$i]['cidade'] = $row['cidade'];
                $service[$i]['campomsgpgtodescricao'] = $row['campomsgpgtodescricao'];
                $service[$i]['campomsgpgtoobrigatorio'] = $row['campomsgpgtoobrigatorio'] == 1 ? true : false;
            }
            echo json_encode($service);
        } else {
            echo '{ "status": "error",  "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
        }
    } else {
        echo '{ "status": "error",  "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
    }

    $oConexao = null;
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
