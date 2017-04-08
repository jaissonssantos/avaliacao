<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();
// function formatar( $data ) {
// 	if( $data == '' ) return '';
//   $d = explode('/', $data);
//   return $d[2] . '-' .$d[1] . '-' . $d[0];
// }

//params json
$params = json_decode(file_get_contents('php://input'));
try {
    if (!isset($_SESSION['ang_plataforma_estabelecimento'])) {
        throw new Exception('Faça o login novamente', 403);
    }

        //pega as informaçoes enviadas para comecar a montar o horario disponivel
        $idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];
        //echo $date;

        //pega as informacoes do profissional para a montagem dos horarios
        $stmt = $oConexao->prepare('SELECT * FROM cliente c, cliente_estabelecimento ce WHERE c.id = ce.idcliente AND ce.idestabelecimento = :idestabelecimento AND ce.status = 1 order by c.nome');
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $m = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo json_encode($m);
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
} catch (Exception $e) {
    echo '{"error":{"text":'.$e->getMessage().'}}';
}
