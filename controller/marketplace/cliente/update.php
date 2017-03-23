<?php

use Utils\Conexao;

header('Content-type: application/json');
$response = new stdClass();
$oConexao = Conexao::getInstance();

try {

    $uid = isset($_SESSION['ang_markday_uid']) ? $_SESSION['ang_markday_uid'] : null;;
    $name = isset($_POST['nome']) ? $_POST['nome'] : null;
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
    $phone = isset($_POST['celular']) ? $_POST['celular'] : null;

    $oConexao->beginTransaction();

    //verifica se o usuário está logado
    if (!isset($_SESSION['ang_markday_uid'])) {
        throw new Exception('Ops! faça o login novamente para atualizar seu perfil', 500);
    }

    //verifica se o arquivo foi enviado
    $setImage = null;
    if (isset($_FILES['imagem'])) {
        //configurações do envio
        $allowedType = array('image/png', 'image/jpeg', 'imagem/jpg', 'image/svg+xml');
        $allowedExt = array('png', 'jpeg', 'jpg', 'svg');
        
        $filepath = $_FILES['imagem']['tmp_name'];
        $type = mime_content_type($filepath);
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $filename = substr(base_convert(md5_file($filepath), 16,32), 0, 12).'.'.$ext;

        // Verifica se o arquivo enviado é permitido
        if (!in_array($type, $allowedType) || !in_array($ext, $allowedExt)) {
            throw new Exception('Formato não permitido', 500);
        }

        // Envia o arquivo
        file_put_contents('s3://cliente/'. $filename, file_get_contents($filepath));
        $setImage = ",imagem='".$filename."'";

        // Apaga a imagem antiga se for diferente da atual
        $stmt = $oConexao->prepare('SELECT imagem FROM cliente WHERE id=? AND imagem IS NOT NULL LIMIT 1');
        $stmt->execute(array($uid));
        $image = $stmt->fetchObject();
        if($image && $image->imagem != $filename) {
            unlink('s3://cliente/' . $image->imagem);
        }
        
        $_SESSION['ang_markday_thumbnail'] = STORAGE_URL . '/cliente/' . $filename;
    }

    // Atualiza o cliente
    $stmt = $oConexao->prepare('UPDATE cliente SET nome=?,cpf=?,telefonecelular=?'.$setImage.' WHERE id=? LIMIT 1');
    $update = $stmt->execute(array(
        $name,
        $cpf,
        $phone,
        $uid
    ));

    if (!$update) {
        throw new Exception('Não foi possível atualizar', 500);
    }

    http_response_code(200);
    $response->success = 'Atualizada com sucesso';
    $oConexao->commit();
    
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
