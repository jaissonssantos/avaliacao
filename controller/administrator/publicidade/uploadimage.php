<?php


header('Content-type: application/json');
$response = new stdClass();

// Configurações do envio
$allowedType = array('image/png', 'image/jpeg', 'imagem/jpg');
$allowedExt = array('png', 'jpeg', 'jpg');
$destination = BASE_DIR . '/assets/img/upload/';

try {
    // Verifica se o arquivo foi enviado
    if (!isset($_FILES['file'])) {
        throw new Exception('Não encontrado', 404);
    }

    // Dados do arquivo
    $filepath = $_FILES['file']['tmp_name'];
    $type = mime_content_type($filepath);
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $filename = substr(base_convert(md5_file($filepath), 16,32), 0, 12).'.'.$ext;
    $destination .= $filename;

    // Verifica se o arquivo enviado é permitido
    if (!in_array($type, $allowedType) || !in_array($ext, $allowedExt)) {
        throw new Exception('Formato não permitido', 500);
    }

    // Comprime imagem jpeg
    if($type === 'image/jpg' || $type === 'image/jpeg') {
        $jpeg = imagecreatefromjpeg($filepath);
        imagejpeg($jpeg,$filepath,90);
    }

    // Move para o diretório de destino
    if (!move_uploaded_file($filepath, $destination)) {
        throw new Exception('Falha no envio', 500);
    }

    // Comprime imagem png
    if($type === 'image/png') {
        system('pngquant --quality=85 --force --ext=.png ' . $destination);
    }

    // Sucesso no envio
    http_response_code(200);
    $response->success = $filename;
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

// Imprime o resultado final
echo json_encode($response);
