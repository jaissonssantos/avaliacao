<?php


use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$estabelecimento = (object) $_POST;
$response = new stdClass();

// Configurações do envio
$allowedType = array('image/png', 'image/jpeg', 'imagem/jpg');
$allowedExt = array('png', 'jpeg', 'jpg');

try {
    if (isset($_FILES['imagem'])) {
        $filepath = $_FILES['imagem']['tmp_name'];
        $type = mime_content_type($filepath);
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $filename = substr(base_convert(md5_file($filepath), 16,32), 0, 12).'.'.$ext;

        // Verifica se o arquivo enviado é permitido
        if (!in_array($type, $allowedType) || !in_array($ext, $allowedExt)) {
            throw new Exception('Formato não permitido', 500);
        }

        file_put_contents('s3://estabelecimento/'. $filename, file_get_contents($filepath));
        $estabelecimento->imagem = $filename;
    }else if(isset($estabelecimento->imagem)){
        $estabelecimento->imagem = basename($estabelecimento->imagem);
    }


    if (!isset(
        $estabelecimento->hash,
        $estabelecimento->cnpjcpf,
        $estabelecimento->razaosocial,
        $estabelecimento->nomefantasia,
        $estabelecimento->sobre,
        $estabelecimento->idsegmento,
        $estabelecimento->email,
        $estabelecimento->telefonecomercial,
        $estabelecimento->cep,
        $estabelecimento->logradouro,
        $estabelecimento->numero,
        $estabelecimento->bairro,
        $estabelecimento->idestado,
        $estabelecimento->idcidade,
        $estabelecimento->licencainicial,
        $estabelecimento->licencafinal,
        $estabelecimento->idplano,
        $estabelecimento->imagem
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    // Remove / (barra) das datas
    $estabelecimento->licencainicial = str_replace('/','',$estabelecimento->licencainicial);
    $estabelecimento->licencafinal = str_replace('/','',$estabelecimento->licencafinal);

    // Converte datas ddMMYYYY para YYYY-mm-dd
    $estabelecimento->licencainicial = date_format(date_create_from_format('dmY', $estabelecimento->licencainicial), 'Y-m-d');
    $estabelecimento->licencafinal = date_format(date_create_from_format('dmY', $estabelecimento->licencafinal), 'Y-m-d');

    if(!$estabelecimento->complemento){
        $estabelecimento->complemento = null; 
    }

    $stmt = $oConexao->prepare('UPDATE estabelecimento SET imagem=?,hash=?,cnpjcpf=?,razaosocial=?,nomefantasia=?,sobre=?,idsegmento=?,email=?,telefonecomercial=?,cep=?,logradouro=?,numero=?,complemento=?,bairro=?,idestado=?,idcidade=?,licencainicial=?,licencafinal=?,idplano=? WHERE id=?');
    $stmt->execute(array(
        $estabelecimento->imagem,
        $estabelecimento->hash,
        $estabelecimento->cnpjcpf,
        $estabelecimento->razaosocial,
        $estabelecimento->nomefantasia,
        $estabelecimento->sobre,
        $estabelecimento->idsegmento,
        $estabelecimento->email,
        $estabelecimento->telefonecomercial,
        $estabelecimento->cep,
        $estabelecimento->logradouro,
        $estabelecimento->numero,
        $estabelecimento->complemento,
        $estabelecimento->bairro,
        $estabelecimento->idestado,
        $estabelecimento->idcidade,
        $estabelecimento->licencainicial,
        $estabelecimento->licencafinal,
        $estabelecimento->idplano,
        $estabelecimento->id,
    ));

    http_response_code(200);
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
    $response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
