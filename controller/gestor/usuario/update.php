<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $usuario = (array) $params;

    $required = array('nome', 'login', 'email', 'perfil', 'id');

    $usuario = array_intersect_key($usuario, array_flip($required));

    if (count($usuario) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Master se o cadastro for de um gestor
    $usuario['master'] = $usuario['perfil'] == 1
                                            ? 1
                                            : 0;

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
        'UPDATE usuario
			SET nome=:nome,login=:login,email=:email,master=:master,
			perfil=:perfil
			WHERE id=:id'
        );
    $stmt->execute($usuario);

    // Apaga todos os profissionais do usuário
    $stmt = $oConexao->prepare(
    'DELETE FROM usuario_profissional
	 	WHERE idusuario = :idusuario
	');
    $stmt->execute(array('idusuario' => $usuario['id']));

    // Apaga todos as permissões do usuário
    $stmt = $oConexao->prepare(
    'DELETE FROM usuario_permissao
	 	WHERE idusuario = :idusuario
	');
    $stmt->execute(array('idusuario' => $usuario['id']));

    // Perfil de profissional -> Associar um profissional ao usuário
    if ($usuario['perfil'] == 3) {
        if (!isset($params->idprofissional)) {
            throw new Exception('Selecione um profissional', 400);
        }

        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_profissional(
				idusuario,idprofissional
			) VALUES (
				:idusuario,:idprofissional
			)');

        $usuario_profissional = array(
            'idusuario' => $usuario['id'],
            'idprofissional' => $params->idprofissional,
        );
        $stmt->execute($usuario_profissional);

        // Permissões do profissional
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
				idusuario,roles
			) VALUES (
				:idusuario,:roles
			)');

        $usuario_permissao = array('idusuario' => $usuario['id']);
        $roles = array('/agenda', '/clientes');

        foreach ($roles as $role) {
            $usuario_permissao['roles'] = $role;
            $stmt->execute($usuario_permissao);
        }

    // Perfil de atendente -> Associar os profissional ao usuário
    } elseif ($usuario['perfil'] == 2) {
        if (!isset($params->profissionais)) {
            throw new Exception('Selecione um profissional', 400);
        }
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_profissional(
				idusuario,idprofissional
			) VALUES (
				:idusuario,:idprofissional
			)');
        $usuario_profissional = array('idusuario' => $usuario['id']);

        $profissionais = $params->profissionais;
        if (is_array($profissionais)) {
            foreach ($profissionais as $profissional) {
                if (isset($profissional->id)) {
                    $usuario_profissional['idprofissional'] = $profissional->id;
                    $stmt->execute($usuario_profissional);
                }
            }
        }

        // Permissões do Atendente
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
				idusuario,roles
			) VALUES (
				:idusuario,:roles
			)');

        $usuario_permissao = array('idusuario' => $usuario['id']);
        $roles = array('/agenda', '/clientes', '/servicos', '/profissionais');

        foreach ($roles as $role) {
            $usuario_permissao['roles'] = $role;
            $stmt->execute($usuario_permissao);
        }
    } elseif ($usuario['perfil'] == 1) {

        // Permissões do Gestor
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
				idusuario,roles
			) VALUES (
				:idusuario,:roles
			)');

        $usuario_permissao = array('idusuario' => $usuario['id']);
        $roles = array(
            '/app', '/agenda', '/clientes', '/servicos', '/profissionais',
            '/usuarios', '/site', '/configuracoes', '/relatorio',
        );
        foreach ($roles as $role) {
            $usuario_permissao['roles'] = $role;
            $stmt->execute($usuario_permissao);
        }
    }

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
    $oConexao->commit();
} catch (PDOException $e) {
    //echo $e->getMessage();
    http_response_code(500);
    $oConexao->rollBack();
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
