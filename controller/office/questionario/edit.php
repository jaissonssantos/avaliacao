<?php

use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$response = new stdClass();

try {
    
    if (!isset(
        $_POST['id'],
        $_POST['titulo'],
        $_POST['pergunta1']
    )) {
        throw new Exception('Verifique os dados preenchidos', 400);
    } 

    //Default params
    $idestabelecimento = $_SESSION['avaliacao_estabelecimento'];
    $idusuario = $_SESSION['avaliacao_uid'];
    $count_tipo = sizeof($_POST['tipo']);
    $prazo = formata_data($_POST['prazodata']).' '.$_POST['prazohora'].':00';

    // Gerar url (hash) do questionário
    $hash = friendlyURL($_POST['titulo']);
    $findHash = true;

    $oConexao->beginTransaction();
    $count = $oConexao->prepare(
        'SELECT 
            COUNT(*) 
        FROM questionario 
        WHERE hash = ? AND id<>?'
    );
    $count->execute(array(
        $hash,
        $_POST['id']
    ));
    $count_results = $count->fetchColumn();

    if($count_results)
        throw new Exception('Tente usar um outro título de questionário', 500);

    $stmt = $oConexao->prepare(
    'UPDATE questionario 
        SET hash=?,idusuario=?,titulo=?,introducao=?,prazo=?,updated_at=now()
        WHERE id=?');

    $stmt->execute(array(
        $hash,
        $idusuario,
        $_POST['titulo'],
        $_POST['introducao'],
        $prazo,
        $_POST['id']
    ));
    $idquestionario = $oConexao->lastInsertId();

    for($i=1; $i<=$count_tipo; $i++){

        $obrigatoria = 0;
        if(!empty($_POST['obrigatoria'.$i]))
            $obrigatoria = $_POST['obrigatoria'.$i];

        $stmt = $oConexao->prepare(
        'UPDATE pergunta
            SET titulo=?,tipo=?,obrigatoria=?
            WHERE id=?');

        $stmt->execute(array(
            $_POST['pergunta'.$i],
            $_POST['tipo'][$i-1],
            $obrigatoria,
            $_POST['perguntaId'][$i-1]
        ));
        $idpergunta = $_POST['perguntaId'][$i-1];

        // Apaga todos as respostas
        $stmt = $oConexao->prepare(
        'DELETE FROM resposta
            WHERE idpergunta=?
        ');
        $stmt->execute(array(
            $idpergunta
        ));

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
    $response->success = 'Atualizado com sucesso';
} catch (PDOException $e) {
    $oConexao->rollBack();
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde: '. $e->getMessage();
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
