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
			us.id,us.nome,us.email,us.login,us.perfil
		FROM usuario us
		WHERE us.id = ?
		LIMIT 1'
    );

    $stmt->execute(array($id));
    $usuario = $stmt->fetchObject();

    $stmt = $oConexao->prepare(
        'SELECT
			pf.id,pf.nome,pf.email,pf.profissao,pf.tempoconsulta
		FROM usuario_profissional up
		INNER JOIN profissional pf ON (pf.id = up.idprofissional)
		WHERE up.idusuario = ?'
    );

    $stmt->execute(array($id));
    $usuario->profissionais = $stmt->fetchAll();

    if (count($usuario->profissionais)) {
        $usuario->idprofissional = $usuario->profissionais[0]->id;
    }

    if (!$usuario) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $usuario;
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
