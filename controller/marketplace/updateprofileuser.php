<?php

use Utils\Conexao;

header('Content-type: application/json');
$response = new stdClass();
$oConexao = Conexao::getInstance();

// Configurações do envio
$allowedType = array('image/png', 'image/jpeg', 'imagem/jpg', 'image/svg+xml');
$allowedExt = array('png', 'jpeg', 'jpg', 'svg');

try {

    // Verifica se o usuário está logado
    if (!isset($_SESSION['ang_markday_uid'])) {
        throw new Exception('Ops! faça o login novamente para atualizar seu perfil', 500);
    }
    $uid = $_SESSION['ang_markday_uid'];

    // Verifica se o arquivo foi enviado
    if (!isset($_FILES['file'])) {
        throw new Exception('Não encontrado', 404);
    }

    // Dados do arquivo
    $filepath = $_FILES['file']['tmp_name'];
    $type = mime_content_type($filepath);
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $filename = substr(base_convert(md5_file($filepath), 16,32), 0, 12).'.'.$ext;

    // Verifica se o arquivo enviado é permitido
    if (!in_array($type, $allowedType) || !in_array($ext, $allowedExt)) {
        throw new Exception('Formato não permitido', 500);
    }

    // Envia o arquivo para o diretório de destino
    if (!file_put_contents('s3://cliente/' . $filename, file_get_contents($filepath))) {
        throw new Exception('Falha no envio', 500);
    }

    // Apaga a imagem antiga
    $stmt = $oConexao->prepare('SELECT imagem as name FROM cliente WHERE id = :id LIMIT 1');
    $stmt->execute(array($uid));
    $image = $stmt->fetchObject();
    unlink('s3://cliente/'.$image->name);

    // Atualiza o cliente
    $stmt = $oConexao->prepare('UPDATE cliente SET imagem=:image WHERE id=:id LIMIT 1');
    $update = $stmt->execute(array($uid));
    if (!$update) {
        throw new Exception('Não foi possível atualizar', 500);
    }
    $response->success = 'Atualizada com sucesso';
    
} catch (PDOException $e) {
    $oConexao->rollBack();
    http_response_code($e->getCode());
    $response->error = 'Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
