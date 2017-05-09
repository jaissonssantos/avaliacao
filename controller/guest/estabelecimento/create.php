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
        $params->senha,
        $params->nomefantasia,
        $params->cpfcnpj,
        $params->telefone,
        $params->cep,
        $params->endereco,
        $params->bairro,
        $params->numero,
        $params->complemento,
        $params->estado,
        $params->cidade
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }
    if(!isset($params->complemento)){
     $params->complemento = null;
    }

    // Gerar url (hash) do estabelecimento
    $params->hash = friendlyURL($params->nomefantasia);
    $findHash = true;

    $oConexao->beginTransaction();

    $count = $oConexao->prepare(
        'SELECT 
            COUNT(*) 
        FROM estabelecimento 
        WHERE hash = ?'
    );
    $count->execute(array($params->hash));
    $count_results = $count->fetchColumn();

    if($count_results)
        throw new Exception('Tente usar um nome fantasia diferente', 500);


    $stmt = $oConexao->prepare('INSERT INTO
                 estabelecimento(hash,nomefantasia,cpfcnpj,telefone,cep,endereco,
                 numero,complemento,bairro,idcidade,created_at,updated_at
                ) VALUES (?,?,?,?,?,?,?,?,?,?,now(),now())');
    $stmt->execute(array(
        $params->hash,
        $params->nomefantasia,
        $params->cpfcnpj, 
        $params->telefone,
        $params->cep,
        $params->endereco,
        $params->numero,
        $params->complemento,
        $params->bairro,
        $params->cidade
    ));    

    $params->senha = sha1(SALT.$params->senha);
    $params->perfil = 1;
    $estabelecimento_id = $oConexao->lastInsertId();

    // Cadastro do usuário - 1 - Usuário comum | 2 - Gestor
    $stmt = $oConexao->prepare('INSERT INTO
                 usuario(nome,sobrenome,email,senha,perfil,idestabelecimento,created_at,updated_at
                ) VALUES (?,?,?,?,?,?,now(),now())');
    $stmt->execute(array(
        $params->nome,
        $params->sobrenome,
        $params->email, 
        $params->senha,
        $params->perfil,
        $estabelecimento_id
    ));

    $usuario_id = $oConexao->lastInsertId();

    // Permissões do Usuário Gestor
    $stmt = $oConexao->prepare(
    'INSERT INTO usuario_permissao(
            idusuario,regra
        ) VALUES (
            :idusuario,:regra
        )');
    $usuario_permissao = array('idusuario' => $usuario_id);
    $regras = array(
        '/dashboard', '/questionarios', '/estabelecimento', '/usuarios', '/relatorio', '/plano', '/404',
    );
    foreach ($regras as $regra) {
        $usuario_permissao['regra'] = $regra;
        $stmt->execute($usuario_permissao);
    }

    $_SESSION['avaliacao_uid'] = $usuario_id;
    $_SESSION['avaliacao_nome'] = $params->nome;
    $_SESSION['avaliacao_sobrenome'] = $params->sobrenome;
    $_SESSION['avaliacao_email'] = $params->email;
    $_SESSION['avaliacao_perfil'] = $params->perfil;
    $_SESSION['avaliacao_gestor'] = false;
    $_SESSION['avaliacao_estabelecimento'] = $estabelecimento_id;

    $oConexao->commit();

    http_response_code(200);
    $response->success = 'Cadastrado sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    // $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
