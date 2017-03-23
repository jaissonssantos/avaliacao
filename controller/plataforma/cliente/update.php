<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    $cliente = (array) $params;

    $required = array(
            'id', 'nome', 'datanascimento', 'cpf', 'email', 'telefonecelular',
            'telefonecomercial', 'cep', 'logradouro', 'numero', 'complemento', 'bairro',
            'idcidade', 'idestado',
    );

    $cliente = array_intersect_key($cliente, array_flip($required));

    if (count($cliente) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Default Parameters
    $cliente['datanascimento'] = substr_replace($cliente['datanascimento'], '-', 2, 0);
    $cliente['datanascimento'] = substr_replace($cliente['datanascimento'], '-', 5, 0);
    $cliente['datanascimento'] = date('Y-m-d', strtotime($cliente['datanascimento']));
    $cliente['idusuario'] = $_SESSION['ang_plataforma_uid'];

    $oConexao->beginTransaction();

    // Atualiza o cliente
    $stmt = $oConexao->prepare(
        'UPDATE cliente
			SET nome=:nome,datanascimento=:datanascimento,cpf=:cpf,email=:email,telefonecelular=:telefonecelular,
				telefonecomercial=:telefonecomercial,cep=:cep,logradouro=:logradouro,numero=:numero,
				complemento=:complemento,bairro=:bairro,idcidade=:idcidade,idestado=:idestado,idusuario=:idusuario
			WHERE id=:id'
    );
    $stmt->execute($cliente);

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
    $oConexao->commit();
} catch (PDOException $e) {
    http_response_code(500);
    $oConexao->rollBack();
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
