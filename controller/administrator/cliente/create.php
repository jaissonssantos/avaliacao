<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $cliente = (array) $params;

    $required = array(
            'nome', 'datanascimento', 'cpf', 'email', 'telefonecelular',
            'telefonecomercial', 'cep', 'logradouro', 'numero', 'complemento', 'bairro',
            'idcidade', 'idestado',
    );

    $cliente = array_intersect_key($cliente, array_flip($required));

    if (count($cliente) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Default Parameters
    $cliente['imagem'] = null;
    $cliente['senha'] = null;
    $cliente['datanascimento'] = substr_replace($cliente['datanascimento'], '-', 2, 0);
    $cliente['datanascimento'] = substr_replace($cliente['datanascimento'], '-', 5, 0);
    $cliente['datanascimento'] = date('Y-m-d', strtotime($cliente['datanascimento']));
    $cliente['idusuario'] = $_SESSION['ang_plataforma_uid'];

    $oConexao->beginTransaction();

    // Inserir o cliente
    $stmt = $oConexao->prepare(
        'INSERT INTO
		cliente(
			nome,imagem,datanascimento,cpf,email,telefonecelular,telefonecomercial,
			cep,logradouro,numero,complemento,bairro,idcidade,idestado,idusuario,
			senha,datacadastro
		) VALUES (
			:nome,:imagem,:datanascimento,:cpf,:email,:telefonecelular,:telefonecomercial,
			:cep,:logradouro,:numero,:complemento,:bairro,:idcidade,:idestado,:idusuario,
			:senha,now())'
    );
    $stmt->execute($cliente);

    // Inserir cliente no estabelecimento
    $cliente_estabelecimento = array(
        'idcliente' => $oConexao->lastInsertId(),
        'idestabelecimento' => $_SESSION['ang_plataforma_estabelecimento'],
        'status' => 1,
    );

    $stmt = $oConexao->prepare(
        'INSERT INTO cliente_estabelecimento(
			idcliente,idestabelecimento,status
		) VALUES (
			:idcliente,:idestabelecimento,:status
		)'
    );
    $stmt->execute($cliente_estabelecimento);

    http_response_code(200);
    $response->success = 'Cadastrado com sucesso';
    $response->id = $cliente_estabelecimento['idcliente'];
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
