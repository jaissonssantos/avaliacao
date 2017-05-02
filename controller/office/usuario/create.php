<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    
    if (!isset(
        $params->nome,
        $params->sobrenome,
        $params->email, 
        $params->perfil
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Default Parameters
    $usuario['idestabelecimento'] = $_SESSION['ang_plataforma_estabelecimento'];
    $usuario['status'] = 1;

    // Master se o cadastro for de um gestor
    $usuario['master'] = $usuario['perfil'] == 1
                                            ? 1
                                            : 0;

    $usuario['senha'] = sha1(SALT.$usuario['senha']);

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
    'INSERT INTO
		usuario(
			nome,login,email,senha,idestabelecimento,perfil,status,
			master,datacadastro
		) VALUES (
			:nome,:login,:email,:senha,:idestabelecimento,:perfil,:status,:master,now()
		)');

    $stmt->execute($usuario);
    $idusuario = $oConexao->lastInsertId();

    // Perfil de profissional
    if ($usuario['perfil'] == 3) {
        if (!isset($usuario['idprofissional'])) {
            throw new Exception('Selecione um profissional', 400);
        }

        // Associar um profissional ao usuário
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_profissional(
				idusuario,idprofissional
			) VALUES (
				:idusuario,:idprofissional
			)');

        $usuario_profissional = array(
            'idusuario' => $idusuario,
            'idprofissional' => $usuario['idprofissional'],
        );
        $stmt->execute($usuario_profissional);

        // Permissões do profissional
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
				idusuario,roles
			) VALUES (
				:idusuario,:roles
			)');

        $usuario_permissao = array('idusuario' => $idusuario);
        $roles = array('/agenda', '/clientes', '/pagamento-do-plano', '/404');

        foreach ($roles as $role) {
            $usuario_permissao['roles'] = $role;
            $stmt->execute($usuario_permissao);
        }

    // Perfil de Atendente
    } elseif ($usuario['perfil'] == 2) {
        if (!isset($params->profissionais)) {
            throw new Exception('Selecione os profissionais', 400);
        }

        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_profissional(
				idusuario,idprofissional
			) VALUES (
				:idusuario,:idprofissional
			)');
        $usuario_profissional = array('idusuario' => $idusuario);

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

        $usuario_permissao = array('idusuario' => $idusuario);
        $roles = array('/agenda', '/clientes', '/servicos', '/profissionais', '/pagamento-do-plano', '/404');

        foreach ($roles as $role) {
            $usuario_permissao['roles'] = $role;
            $stmt->execute($usuario_permissao);
        }

    // Perfil de Gestor
    } elseif ($usuario['perfil'] == 1) {

        // Permissões do Gestor
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
				idusuario,roles
			) VALUES (
				:idusuario,:roles
			)');

        $usuario_permissao = array('idusuario' => $idusuario);
        $roles = array(
            '/dashboard', '/agenda', '/clientes', '/servicos', '/profissionais',
            '/usuarios', '/site', '/configuracoes', '/relatorio', '/pagamento-do-plano', '/404',
        );
        foreach ($roles as $role) {
            $usuario_permissao['roles'] = $role;
            $stmt->execute($usuario_permissao);
        }
    }

    http_response_code(200);
    $response->success = 'Cadastrado com sucesso';
    $oConexao->commit();
} catch (PDOException $e) {
    //echo $e->getMessage();
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
