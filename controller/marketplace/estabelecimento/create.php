<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();
setlocale(LC_ALL, 'pt_BR.UTF8');

try {
    if (!isset(
        $params->usuario->nome,
        $params->usuario->sobrenome,
        $params->usuario->email, 
        $params->usuario->senha,
        $params->nomefantasia,
        $params->cpfcnpj,
        $params->telefone,
        $params->cep,
        $params->endereco,
        $params->bairro,
        $params->numero,
        $params->complemento,
        $params->idestado,
        $params->idcidade
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }
    if(!isset($params->complemento)){
     $params->complemento = null;
    }

    // Gerar url (hash) do estabelecimento
    $params->hash = friendlyURL($params->nomefantasia);
    $loops = 0;
    $findHash = true;

    $oConexao->beginTransaction();

    $stmt = $oConexao->prepare('SELECT hash FROM estabelecimento WHERE hash = ? LIMIT 1');
    while($findHash){
        $stmt->execute(array($params->hash));
        $findHash = $stmt->fetchObject();
        if($findHash) $estabelecimento->hash = $params->hash . '-'. $loops . rand(0,9999);
        if($loops > 20) {
            throw new Exception('Tente usar um nome fantasia diferente', 500);
        }
        $loops++;
    }

    $stmt = $oConexao->prepare('INSERT INTO
                 estabelecimento(hash,nomefantasia,cpfcnpj,telefone,cep,endereco,
                 numero,complemento,bairro,idcidade,created_at,updated_at
                ) VALUES (?,?,?,?,?,?,?,?,?,now(),now())');
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
        $params->idcidade
    ));    

    $params->usuario->senha = sha1(SALT.$params->usuario->senha);
    $params->usuario->perfil = 1;
    $estabelecimento_id = $oConexao->lastInsertId();

    // Cadastro do usuário - 1 - Usuário comum | 2 - Gestor
    $stmt = $oConexao->prepare('INSERT INTO
                 usuario(nome,sobrenome,email,senha,perfil,idestabelecimento
                ) VALUES (?,?,?,?,?,?)');
    $stmt->execute(array(
        $params->usuario->nome,
        $params->usuario->sobrenome,
        $params->usuario->email, 
        $params->usuario->senha,
        $params->usuario->perfil,
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
    foreach ($roles as $role) {
        $usuario_permissao['regra'] = $regras;
        $stmt->execute($usuario_permissao);
    }

    $_SESSION['ang_avaliame_uid'] = $usuario_id;
    $_SESSION['ang_avaliame_nome'] = $params->usuario->nome;
    $_SESSION['ang_avaliame_sobrenome'] = $params->usuario->sobrenome;
    $_SESSION['ang_avaliame_email'] = $params->usuario->email;
    $_SESSION['ang_avaliame_perfil'] = $params->usuario->perfil
    $_SESSION['ang_avaliame_estabelecimento'] = $estabelecimento_id;

    $oConexao->commit();

    http_response_code(200);
    $response->success = 'Cadastrado sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    //$response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
