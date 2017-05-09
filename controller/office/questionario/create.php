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
    $idusuario = $_SESSION['avaliacao_uid'];
    $count_tipo = sizeof($_POST['tipo']);

    // Gerar url (hash) do questionário
  //   $hash = friendlyURL($_POST['titulo']);
  //   $findHash = true;

  //   $oConexao->beginTransaction();
  //   $count = $oConexao->prepare(
  //       'SELECT 
  //           COUNT(*) 
  //       FROM questionario 
  //       WHERE hash = ?'
  //   );
  //   $count->execute(array($hash));
  //   $count_results = $count->fetchColumn();

  //   if($count_results)
  //       throw new Exception('Tente usar um outro título de questionário', 500);

  //   $stmt = $oConexao->prepare(
  //   'INSERT INTO
		// questionario(
		// 	hash,idusuario,titulo,introducao,status,created_at,updated_at
		// ) VALUES (
		// 	?,?,?,?,1,now(),now()
		// )');

  //   $stmt->execute(array(
  //       $hash,
  //       $idusuario,
  //       $_POST['titulo'],
  //       $_POST['introducao']
  //   ));
  //   $idquestionario = $oConexao->lastInsertId();

  //   $stmt = $oConexao->prepare(
  //       'INSERT INTO pergunta(
  //               idquestionario,titulo,tipo,status
  //           ) VALUES (
  //               :idusuario,:regra
  //           )');

    for($i=1; $i<=$count_tipo; $i++){
        echo $_POST['pergunta'.$i];
        
    }
    // foreach ($_POST['pergunta1'] as $p) {
    //     $pergunta['regra'] = $p;
    //     $stmt->execute($usuario_permissao);
    // }

  //   // Perfil de Acesso comum
  //   if ($params->perfil == 1) {
        
  //       $stmt = $oConexao->prepare(
  //       'INSERT INTO usuario_permissao(
  //               idusuario,regra
  //           ) VALUES (
  //               :idusuario,:regra
  //           )');

  //       $usuario_permissao = array('idusuario' => $idusuario);
  //       $regras = array('/dashboard', '/questionarios', '/relatorios');

  //       foreach ($regras as $regra) {
  //           $usuario_permissao['regra'] = $regra;
  //           $stmt->execute($usuario_permissao);
  //       }

  //   // Perfil de gestor
  //   } else if ($params->perfil == 2) {
        
        
  //   } 

    // $oConexao->commit();

    http_response_code(200);
    $response->success = 'Cadastrado com sucesso'. $count_tipo;
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
