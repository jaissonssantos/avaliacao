<?php
if (!session_id()) {
    session_start();
}

if(isset($_GET['error'])) {
    header('Location: /home');
}

use Utils\Conexao;
use Facebook\Facebook;

//header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$response = new stdClass();

$fb = new Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();

    if (!$accessToken->isLongLived()) {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    }

    $_SESSION['fb_access_token'] = (string) $accessToken;
    $oResponse = $fb->get('/me?fields=id,name,email', $accessToken);
    $graphNode  = $oResponse->getGraphNode();

    // Verificar se o email já foi utilizado ou se já logou com facebook.
    $stmt = $oConexao->prepare(
            'SELECT
                cl.id,cl.nome,cl.imagem,cl.datanascimento,cl.cpf,cl.email,cl.fbid,
                cl.telefonecelular,cl.telefonecomercial,cl.cep,cl.logradouro,
                cl.numero,cl.complemento,cl.bairro,cl.idcidade,cl.idestado,cl.senha
            FROM cliente cl
            WHERE cl.email = :email OR cl.fbid = :fbid
            LIMIT 1'
        );

    $stmt->execute(array(
            'email' => $graphNode->getField('email'),
            'fbid' => $graphNode->getField('id'))
    );
    $cliente = $stmt->fetchObject();

    // Salva a imagem do perfil do Facebook
    $imageurl = 'https://graph.facebook.com/'.$graphNode->getField('id').'/picture?type=large';
    $imageContents = file_get_contents($imageurl);
    $imagename = substr(base_convert(md5($imageContents), 16, 32), 0, 12).'.jpg';
    $destination = 's3://cliente/' . $imagename;
    $put = file_put_contents($destination, $imageContents);

    // Caso o cliente tenha definido uma senha, será necessário que faça o login com senha.
    if ($cliente && $cliente->senha != null && $cliente->fbid != $graphNode->getField('id')) {
        $_SESSION['ang_markday_email'] = $cliente->email;
        header('Location: /login');
    }

    $clienteData = array(
        'email' => $graphNode->getField('email'),
        'nome' => $graphNode->getField('name'),
        'datanascimento' => date('Y-m-d', strtotime('2010-01-01')),
        'imagem' => $imagename,
        'fbid' => $graphNode->getField('id')
    );

    if (!$cliente) {
        // Cadastrar o cliente.
        $stmt = $oConexao->prepare(
                'INSERT INTO
                cliente(
                    nome,datanascimento,email,imagem,fbid,datacadastro
                ) VALUES (
                    :nome,:datanascimento,:email,:imagem,:fbid,now())'
            );
        $stmt->execute($clienteData);
        $cliente = (object) $clienteData;
        $cliente->id = $oConexao->lastInsertId();
        $cliente->cpf = null;
    } else {
        // Atualizar o cliente
        $stmt = $oConexao->prepare('
            UPDATE cliente cl SET cl.nome=:nome,cl.imagem=:imagem,cl.datanascimento=:datanascimento,cl.fbid=:fbid
            WHERE cl.email = :email
            LIMIT 1
        ');
        $stmt->execute($clienteData);
        $cliente = (object) array_merge(get_object_vars($cliente), $clienteData);
    }

    $_SESSION['ang_markday_uid'] = $cliente->id;
    $_SESSION['ang_markday_name'] = $cliente->nome;
    $_SESSION['ang_markday_cpf'] = $cliente->cpf;
    $_SESSION['ang_markday_email'] = $cliente->email;
    $_SESSION['ang_markday_thumbnail'] = STORAGE_URL . '/cliente/' . $cliente->imagem;

    header('Location: /cliente');
} catch (PDOException $e) {
    echo $e->getMessage();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
