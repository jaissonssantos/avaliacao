<?php


use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

// Configurações do envio
$allowedType = array('image/png', 'image/jpeg', 'imagem/jpg');
$allowedExt = array('png', 'jpeg', 'jpg');

try {
    $estabelecimento = (array) $_POST;

    // Parâmetros requeridos
    $required = array('nomefantasia', 'sobre', 'telefonecomercial');
    $estabelecimento = array_intersect_key($estabelecimento, array_flip($required));

    if (count($estabelecimento) != count($required)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    //get session local browser
    $idEstabelecimento = $_SESSION['ang_plataforma_estabelecimento'];

    $setImage = null;
    if (isset($_FILES['principal'])) {
        $filepath = $_FILES['principal']['tmp_name'];
        $type = mime_content_type($filepath);
        $ext = pathinfo($_FILES['principal']['name'], PATHINFO_EXTENSION);
        $filename = substr(base_convert(md5_file($filepath), 16,32), 0, 12).'.'.$ext;

        // Verifica se o arquivo enviado é permitido
        if (!in_array($type, $allowedType) || !in_array($ext, $allowedExt)) {
            throw new Exception('Formato não permitido', 500);
        }

        // Envia o arquivo
        file_put_contents('s3://estabelecimento/'. $filename, file_get_contents($filepath));
        $setImage = "imagem='".$filename."',";
    }
    

    // Atualiza as novas informações do estabelecimento
    $stmt = $oConexao->prepare('UPDATE estabelecimento 
                                    SET '.$setImage.' nomefantasia=:nomefantasia, sobre=:sobre, telefonecomercial=:telefonecomercial
                                WHERE id=:idestabelecimento'
    );
    $estabelecimento = $stmt->execute(array(
        'nomefantasia' => $estabelecimento['nomefantasia'],
        'sobre' => $estabelecimento['sobre'],
        'telefonecomercial' => $estabelecimento['telefonecomercial'],
        'idestabelecimento' => $idEstabelecimento)
    );

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

echo json_encode($response);
