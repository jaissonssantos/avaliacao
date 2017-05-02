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
        $params->perfil,
        $params->senha
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Default Parameters
    $params->idestabelecimento = $_SESSION['avaliacao_estabelecimento'];
    $params->senha = sha1(SALT.$params->senha);

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare(
    'INSERT INTO
		usuario(
			nome,sobrenome,email,senha,idestabelecimento,perfil,created_at,updated_at
		) VALUES (
			?,?,?,?,?,?,now(),now()
		)');

    $stmt->execute(array(
        $params->nome,
        $params->sobrenome,
        $params->email,
        $params->senha,
        $params->idestabelecimento,
        $params->perfil
    ));
    $idusuario = $oConexao->lastInsertId();

    // Perfil de Acesso comum
    if ($params->perfil == 1) {
        
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
                idusuario,regra
            ) VALUES (
                :idusuario,:regra
            )');

        $usuario_permissao = array('idusuario' => $idusuario);
        $regras = array('/dashboard', '/questionarios', '/relatorios');

        foreach ($regras as $regra) {
            $usuario_permissao['regra'] = $regra;
            $stmt->execute($usuario_permissao);
        }

    // Perfil de gestor
    } else if ($params->perfil == 2) {
        
        $stmt = $oConexao->prepare(
        'INSERT INTO usuario_permissao(
                idusuario,regra
            ) VALUES (
                :idusuario,:regra
            )');

        $usuario_permissao = array('idusuario' => $idusuario);
        $regras = array('/dashboard', '/questionarios', '/usuarios', '/relatorios');

        foreach ($regras as $regra) {
            $usuario_permissao['regra'] = $regra;
            $stmt->execute($usuario_permissao);
        }
    } 

    $oConexao->commit();

    http_response_code(200);
    $response->success = 'Cadastrado com sucesso';
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
