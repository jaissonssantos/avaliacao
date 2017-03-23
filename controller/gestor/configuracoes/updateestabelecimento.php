<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$nomefantasia = $_POST['nomefantasia'];
$sobre = $_POST['sobre'];
$fileone = $_FILES['fileone'];
$filetwo = $_FILES['filetwo'];
$filethree = $_FILES['filethree'];
$files = $_POST['imagem'];
$deletarimagem = $_POST['imagemdeletar'];
$path = '../../assets/img/empresas';

//get session local browser
$idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];
$response = new stdClass();

try {
    if (!isset($nomefantasia) || !isset($sobre)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    if (isset($fileone)) {
        $tmpfile = $fileone['tmp_name'];
        $extension = strtolower(end(explode('.', $fileone['name'])));
        $newnamefile = md5(uniqid(time())).'.'.$extension;
        $uploadpath = $path.DIRECTORY_SEPARATOR.$newnamefile;
        if (move_uploaded_file($tmpfile, $uploadpath)) {
            //deleta item da tabela
            $stmt = $oConexao->prepare(
                'UPDATE estabelecimento_imagem SET
					imagem=:file, principal=1, ordem=1
				WHERE id=:id'
            );
            $stmt->execute(array('id' => $files[0]['id'], 'file' => $newnamefile));
            //deleta o arquivo da pasta
            unlink($path.DIRECTORY_SEPARATOR.$files[0]['file']);
        } else {
            throw new Exception('Ops! Tivemos um problema, verifique a imagem principal enviada', 400);
        }
    }

    if (isset($filetwo)) {
        $tmpfile = $filetwo['tmp_name'];
        $extension = strtolower(end(explode('.', $filetwo['name'])));
        $newnamefile = md5(uniqid(time())).'.'.$extension;
        $uploadpath = $path.DIRECTORY_SEPARATOR.$newnamefile;
        if (move_uploaded_file($tmpfile, $uploadpath)) {
            //deleta item da tabela
            $stmt = $oConexao->prepare(
                'UPDATE estabelecimento_imagem SET
					imagem=:file, principal=0, ordem=2
				WHERE id=:id'
            );
            $stmt->execute(array('id' => $files[1]['id'], 'file' => $newnamefile));
            //deleta o arquivo da pasta
            unlink($path.DIRECTORY_SEPARATOR.$files[1]['file']);
        } else {
            throw new Exception('Ops! Tivemos um problema, verifique a segunda imagem enviado', 400);
        }
    }

    if (isset($filethree)) {
        $tmpfile = $filethree['tmp_name'];
        $extension = strtolower(end(explode('.', $filethree['name'])));
        $newnamefile = md5(uniqid(time())).'.'.$extension;
        $uploadpath = $path.DIRECTORY_SEPARATOR.$newnamefile;
        if (move_uploaded_file($tmpfile, $uploadpath)) {
            //deleta item da tabela
            $stmt = $oConexao->prepare(
                'UPDATE estabelecimento_imagem SET
					imagem=:file, principal=0, ordem=3
				WHERE id=:id'
            );
            $stmt->execute(array('id' => $files[2]['id'], 'file' => $newnamefile));
            //deleta o arquivo da pasta
            unlink($path.DIRECTORY_SEPARATOR.$files[2]['file']);
        } else {
            throw new Exception('Ops! Tivemos um problema, verifique a terceira imagem enviado', 400);
        }
    }

    //deletar segunda e terceira imagem, caso esteja no array
    if (sizeof($deletarimagem) > 0) {
        $stmt = $oConexao->prepare(
            'DELETE FROM estabelecimento_imagem
			WHERE id=:id'
        );
        foreach ($deletarimagem as $item) {
            $stmt->execute(array('id' => $item['id']));
            //deleta o arquivo da pasta
            unlink($path.DIRECTORY_SEPARATOR.$item['file']);
        }
    }

    //Atualiza as novas informações do estabelecimento
    $stmt = $oConexao->prepare(
            'UPDATE estabelecimento 
				SET nomefantasia=:nomefantasia, sobre=:sobre
			WHERE id=:idestabelecimento'
    );
    $estabelecimento = $stmt->execute(array('nomefantasia' => $nomefantasia, 'sobre' => $sobre, 'idestabelecimento' => $idestabelecimento));

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
