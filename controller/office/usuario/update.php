<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset(
        $params->id,
        $params->nome,
        $params->sobrenome,
        $params->email, 
        $params->perfil
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'UPDATE usuario
			SET nome=?,sobrenome=?,email=?,perfil=?
			WHERE id=?'
        );
    $stmt->execute(array(
        $params->nome,
        $params->sobrenome, 
        $params->email,
        $params->perfil,
        $params->id
    )); 

    // Apaga todos as permissões do usuário
    $stmt = $oConexao->prepare(
    'DELETE FROM usuario_permissao
	 	WHERE idusuario=?
	');
    $stmt->execute(array(
        $params->id
    ));

    // Perfil de Acesso comum
    if ($params->perfil == 1) {

        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
				idusuario,regra
			) VALUES (
				:idusuario,:regra
			)');

        $usuario_permissao = array('idusuario' => $params->id);
        $regras = array('/dashboard', '/questionarios', '/relatorios');

        foreach ($regras as $regra) {
            $usuario_permissao['regra'] = $regra;
            $stmt->execute($usuario_permissao);
        }

    // Perfil de gestor
    } else if ($params->perfil == 2) {

        // Permissões do Atendente
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
				idusuario,regra
			) VALUES (
				:idusuario,:regra
			)');

        $usuario_permissao = array('idusuario' => $params->id);
        $regras = array('/dashboard', '/questionarios', '/usuarios', '/relatorios');

        foreach ($regras as $regra) {
            $usuario_permissao['regra'] = $regra;
            $stmt->execute($usuario_permissao);
        }
    }

    $oConexao->commit();

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $oConexao->rollBack();
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde: '. $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
