<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$response = new stdClass();

try {
    
    if (!isset(
        $_POST['hash']
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    } 

    //Default params
    $idcliente = 1; //$_SESSION['avaliacao_cliente_uid'];
    $count_pergunta = sizeof($_POST['pergunta']);

    $oConexao->beginTransaction();

    for($i=1; $i<=$count_pergunta; $i++){

        $tipo = $_POST['tipo'][$i-1];
        $pergunta = $_POST['pergunta'][$i-1];

        switch ($tipo) {
            case 1:
                $stmt = $oConexao->prepare(
                'INSERT INTO resposta(
                        idcliente,idpergunta,resposta
                    ) VALUES (
                        ?,?,?
                    )');
                $stmt->execute(array(
                    $idcliente,
                    $pergunta,
                    $_POST['resposta'.$pergunta]
                ));
                break;

            case 2:
                $stmt = $oConexao->prepare(
                'INSERT INTO resposta_cliente(
                        idcliente,idresposta
                    ) VALUES (
                        ?,?
                    )');
                $stmt->execute(array(
                    $idcliente,
                    $_POST['resposta'.$pergunta]
                ));
                break;

            case 3:
                if(!empty($_POST['resposta'.$pergunta])){
                    foreach($_POST['resposta'.$pergunta] as $resposta){
                        $stmt = $oConexao->prepare(
                        'INSERT INTO resposta_cliente(
                                idcliente,idresposta
                            ) VALUES (
                                ?,?
                            )');
                        $stmt->execute(array(
                            $idcliente,
                            $resposta
                        ));
                    }
                }
                break;
        }
    }

    $oConexao->commit();

    http_response_code(200);
    $response->success = 'Cadastrado com sucesso';
} catch (PDOException $e) {
    //echo $e->getMessage();
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
