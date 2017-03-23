<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

if (!isset($params->id)) {
    throw new Exception('Verifique os dados preenchidos', 400);
}

$id = $params->id;

try {
    $stmt = $oConexao->prepare(
        'SELECT
			pf.id,pf.nome,pf.email,pf.profissao
		FROM profissional pf
		WHERE pf.id = ?
		LIMIT 1'
    );

    $stmt->execute(array($id));
    $profissional = $stmt->fetchObject();

    $stmt = $oConexao->prepare(
        'SELECT
			sv.id,sv.nome,sv.duracao,sv.sobconsulta,sv.valor,sv.valorpromocao,sv.promocao
		FROM profissional_servico ps
		INNER JOIN servico sv ON (sv.id = ps.idservico)
		WHERE ps.idprofissional = ?'
    );

    $stmt->execute(array($id));
    $profissional->servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $oConexao->prepare(
        "SELECT
			pd.dia,
			DATE_FORMAT(pd.horainicial,'%H%i') AS horainicial,
			DATE_FORMAT(pd.horafinal,'%H%i') AS horafinal
		FROM profissional_diastrabalho pd
		WHERE pd.idprofissional = ?"
    );

    $stmt->execute(array($id));
    $profissional->diastrabalho = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$profissional) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $profissional;
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
