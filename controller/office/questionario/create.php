<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$response = new stdClass();

try {
    
    if (!isset(
        $_POST['titulo'],
        $_POST['pergunta1']
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    } 

    //Default params
    $idestabelecimento = $_SESSION['avaliacao_estabelecimento'];
    $idusuario = $_SESSION['avaliacao_uid'];
    $count_tipo = sizeof($_POST['tipo']);

    // Gerar url (hash) do questionário
    $hash = friendlyURL($_POST['titulo']);
    $findHash = true;

    $oConexao->beginTransaction();
    $count = $oConexao->prepare(
        'SELECT 
            COUNT(*) 
        FROM questionario 
        WHERE hash = ?'
    );
    $count->execute(array($hash));
    $count_results = $count->fetchColumn();

    if($count_results)
        throw new Exception('Tente usar um outro título de questionário', 500);

    $stmt = $oConexao->prepare(
    'INSERT INTO
		questionario(
			hash,idestabelecimento,idusuario,titulo,introducao,status,created_at,updated_at
		) VALUES (
			?,?,?,?,1,now(),now()
		)');

    $stmt->execute(array(
        $hash,
        $idestabelecimento,
        $idusuario,
        $_POST['titulo'],
        $_POST['introducao']
    ));
    $idquestionario = $oConexao->lastInsertId();


    for($i=1; $i<=$count_tipo; $i++){

        if(!empty($_POST['obrigatoria'.$i-1])){
            $obrigatoria = $_POST['obrigatoria'.$i];
        }else{
            $obrigatoria = null;
        }

        $stmt = $oConexao->prepare(
        'INSERT INTO pergunta(
                idquestionario,titulo,tipo,obrigatoria
            ) VALUES (
                ?,?,?
            )');
        $stmt->execute(array(
            $idquestionario,
            $_POST['pergunta'.$i],
            $_POST['tipo'][$i-1],
            $obrigatoria
        ));
        $idpergunta = $oConexao->lastInsertId();

        if(!empty($_POST['resposta'.$i])){
            $count_resposta = sizeof($_POST['resposta'.$i]);
            for($x=0; $x<$count_resposta; $x++){ 
                $stmt = $oConexao->prepare(
                'INSERT INTO resposta(
                        idpergunta,titulo
                    ) VALUES (
                        ?,?
                    )');
                $stmt->execute(array(
                    $idpergunta,
                    $_POST['resposta'.$i][$x]
                ));
            }
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
