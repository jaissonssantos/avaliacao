<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {

    $idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];

    if (!isset($idestabelecimento)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $offset = isset($params->offset) && $params->offset > 0
                        ? $params->offset
                        : 0;
    $limit = isset($params->limit) && $params->limit < 200
                        ? $params->limit
                        : 200;

    $stmt = $oConexao->prepare(
        'SELECT 
                ag.id,horainicial as dtainicio,horafinal as dtafim,
                ag.observacao as nota,ag.status, 
                pf.id as idprofissional,pf.nome as profissional,pf.profissao, 
                cl.nome as cliente,cl.telefonecelular as celular,cl.email,
                (select group_concat(
                    concat(" ", sv.nome )
                    ) as servicos
                    from agendamento_servico ags
                    inner join servico sv on sv.id = ags.idservico
                    where ags.idagendamento = ag.id
                ) as servico
            FROM agendamento ag 
            LEFT JOIN estabelecimento et ON(ag.idestabelecimento = et.id)
            LEFT JOIN profissional pf ON(ag.idprofissional = pf.id)
            LEFT JOIN cliente cl ON(ag.idcliente = cl.id)
        WHERE ag.idestabelecimento=:idestabelecimento
        LIMIT :offset,:limit'
    );
    $stmt->bindParam('idestabelecimento', $idestabelecimento);
    $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam('limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = $oConexao->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);

    http_response_code(200);
    $response = array(
        'results' => $results,
        'count' => $count,
    );

} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    echo $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
