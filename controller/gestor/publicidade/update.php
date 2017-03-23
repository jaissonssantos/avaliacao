<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

$upload_path = BASE_DIR . '/assets/img/upload/';
$destination = BASE_DIR . '/assets/img/destaques/';

try {
    // Verifica se o paramêtros foram enviados conrretamente
    $publicidade = (array) $params;
    $required = array(
        'id',
        'nome',
        'idestabelecimento',
        'tipo',
        'data_inicio',
        'data_fim',
        'path_imagem',
        'url',
        'descricao',
        'valor',
        'ordenacao',
    );
    $publicidade = array_intersect_key($publicidade, array_flip($required));
    if (count($publicidade) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }
    $publicidade['data_inicio'] = _formatDate($publicidade['data_inicio']);
    $publicidade['data_fim'] = _formatDate($publicidade['data_fim']);

    // Obtem a imagem antiga
    $stmt = $oConexao->prepare('SELECT path_imagem as name FROM publicidade WHERE id = :id LIMIT 1');
    $stmt->bindParam('id', $publicidade['id'], PDO::PARAM_INT);
    $stmt->execute();
    $oldimage = $stmt->fetchObject();

    // Executa a atualização
    $stmt = $oConexao->prepare(
            'UPDATE publicidade SET 
				idestabelecimento=:idestabelecimento,nome=:nome,
				tipo=:tipo,data_inicio=:data_inicio,ordenacao=:ordenacao,
				data_fim=:data_fim,path_imagem=:path_imagem,url=:url,
				descricao=:descricao,valor=:valor
			WHERE id=:id'
    );
    $stmt->execute($publicidade);
    
    $image = $publicidade['path_imagem'];
    $uploaded = $upload_path . $image;

    // Move a nova imagem e apaga a antiga
    if (file_exists($uploaded)) {
        rename($uploaded, $destination . $image);
        // Apaga a imagem antiga se for diferente da nova
        if ($destination . $image !== $destination.$oldimage->name) {
            unlink($destination.$oldimage->name);
        }
    }

    if (!file_exists($destination . $image)) {
        throw new Exception('Falha no envio', 500);
    }

    // Sucesso na atualização
    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
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
    $date = str_replace('/', '', $date);
    $date = substr_replace($date, '-', 2, 0);
    $date = substr_replace($date, '-', 5, 0);

    return date('Y-m-d', strtotime($date));
}

// Imprime o resultado final
echo json_encode($response);
