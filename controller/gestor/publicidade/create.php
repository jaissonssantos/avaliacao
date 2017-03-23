<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

$upload_path = BASE_DIR . '/assets/img/upload/';
$destination = BASE_DIR . '/assets/img/destaques/';

try {
    $publicidade = (array) $params;

    $required = array(
        'idestabelecimento',
        'nome',
        'tipo',
        'data_inicio',
        'data_fim',
        'valor',
        'path_imagem',
        'url',
        'descricao',
        'ordenacao',
    );

    $publicidade = array_intersect_key($publicidade, array_flip($required));
    if (count($publicidade) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Default Parameters
    $publicidade['status'] = 1;
    $publicidade['idusuario'] = 1; //TODO: Verificar
    $publicidade['hash'] = uniqid();
    $publicidade['data_inicio'] = _formatDate($publicidade['data_inicio']);
    $publicidade['data_fim'] = _formatDate($publicidade['data_fim']);

    // Inserir o publicidade
    $stmt = $oConexao->prepare(
        'INSERT INTO
			publicidade(
				idestabelecimento,nome,tipo,data_inicio,data_fim,valor,path_imagem,
				url,descricao,hash,status,ordenacao,idusuario,datacadastro
		) VALUES (
				:idestabelecimento,:nome,:tipo,:data_inicio,:data_fim,:valor,:path_imagem,
				:url,:descricao,:hash,:status,:ordenacao,:idusuario,now()
		)'
    );

    $stmt->execute($publicidade);

    // Move a imagem enviada para a pasta de destino
    $image = $publicidade['path_imagem'];
    $uploaded = $upload_path . $image;
    if(file_exists($uploaded)) {
        rename($uploaded, $destination . $image);
    }

    http_response_code(200);
    $response->success = 'Cadastrada com sucesso';
    $response->id = $oConexao->lastInsertId();
} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

function _formatDate($date)
{
    $date = substr_replace($date, '-', 2, 0);
    $date = substr_replace($date, '-', 5, 0);

    return date('Y-m-d', strtotime($date));
}

echo json_encode($response);
