<?php
use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    if (!isset($params->servicos)) {
        throw new Exception('Selecione os serviços', 400);
    }

    if (!isset($params->profissional)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $hash = generatehash();
    $idusuario = $_SESSION['ang_plataforma_uid'];
    $idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];
    $idconvenio = isset($params->convenio) ?
                                    $params->convenio
                                    : null;
    $observacao = isset($params->observacao) ?
                                    $params->observacao
                                    : null;

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'INSERT INTO 
			agendamento(
				hash,idcliente,idprofissional,idconvenio,horainicial,horafinal,status,idusuario,idestabelecimento,observacao,datacadastro
			) VALUES (?,?,?,?,?,?,1,?,?,?,now())'
    );
    $stmt->execute(array(
        $hash,
        $params->cliente->id,
        $params->profissional,
        $idconvenio,
        $params->dtainicio,
        $params->dtafim,
        $idusuario,
        $idestabelecimento,
        $observacao
    ));
    $idagendamento = $oConexao->lastInsertId();

    $stmt = $oConexao->prepare(
        'INSERT INTO 
			agendamento_servico(
				idservico,idagendamento
			) VALUES (?,?)'
    );
    $servicos = $params->servicos;
    foreach ($servicos as $servico) {
        if (isset($servico->id)) {
            $stmt->execute(array(
                $servico->id,
                $idagendamento
            ));
        }
    }

    $stmt = $oConexao->prepare(
        'SELECT 
                ag.id,horainicial as dtainicio,horafinal as dtafim,
                ag.status,pf.id as idprofissional,cl.nome as cliente
            FROM agendamento ag 
            LEFT JOIN estabelecimento et ON(ag.idestabelecimento = et.id)
            LEFT JOIN profissional pf ON(ag.idprofissional = pf.id)
            LEFT JOIN cliente cl ON(ag.idcliente = cl.id)
            WHERE 
                ag.id=:id 
            AND 
                ag.idestabelecimento=:idestabelecimento
        LIMIT 1'
    );
    $stmt->execute(array('id' => $idagendamento, 'idestabelecimento' => $idestabelecimento));
    $results = $stmt->fetchObject();

    if (!$results) {
        throw new Exception('Não encontrado', 404);
    }

    $oConexao->commit();
    http_response_code(200);

    $response = array(
        'results' => $results,
        'success' => 'Pronto! Agendamento realizado com sucesso',
    );
    
} catch (PDOException $e) {
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    $oConexao->rollBack();
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
