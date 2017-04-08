<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

//get session local browser
$idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];
$response = new stdClass();

try {
    if (!isset($params->tags) || count($params->tags) == 0) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    //deleta todas a tags do estabelecimento
    $stmt = $oConexao->prepare(
        'DELETE FROM tags
		WHERE idestabelecimento=:idestabelecimento'
    );
    $stmt->execute(array('idestabelecimento' => $idestabelecimento));

    //Adiciona as novas tag's
    $stmt = $oConexao->prepare(
            'INSERT INTO tags(
				nome, idestabelecimento
			) VALUES (
				:tag, :idestabelecimento
			)'
    );
    foreach ($params->tags as $tag) {
        $tags_update = array(
            'idestabelecimento' => $idestabelecimento,
            'tag' => $tag->text,
        );
        $stmt->execute($tags_update);
    }

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
