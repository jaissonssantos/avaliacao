<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();
//params json
$idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];
$response = new stdClass();

if (!isset($_SESSION['ang_plataforma_estabelecimento'])) {
    throw new Exception('Ops! faça o seu login e tente novamento', 400);
}

try {
    $stmt = $oConexao->prepare(
            'SELECT 
				descricao as description 
			FROM estabelecimento_lembrete 
			WHERE idestabelecimento = ?
			LIMIT 1');
    $stmt->execute(array($idestabelecimento)); //hash estabelecimento
        $lembrete = $stmt->fetchObject();

    if (!$lembrete) {
        throw new Exception('Não encontrado', 404);
    }

    http_response_code(200);
    $response = $lembrete;
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
