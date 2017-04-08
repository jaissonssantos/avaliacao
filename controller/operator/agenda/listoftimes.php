<?php


use Utils\Conexao;

header('Content-type: application/json');
$oConexao = Conexao::getInstance();
$params = json_decode(file_get_contents('php://input'));
$response = new stdClass();

try {
    if (!isset($params->data)) {
        throw new Exception('Verifique os dados preenchidos', 400);
    }

    //pega as informaçoes enviadas para comecar a montar o horario disponivel
    $date = date_create(formatar($params->data));
    $datemarket = date_format($date, 'Y-m-d');
    $dia = diasemana($datemarket);
    $profissional = $params->professional;

    //pega as informacoes do profissional para a montagem dos horarios
    $stmt = $oConexao->prepare('SELECT * FROM profissional p, profissional_diastrabalho pd 
								WHERE
									p.id = pd.idprofissional AND
									pd.dia = :dia AND
									p.id = :id');
    $stmt->bindValue('dia', $dia);
    $stmt->bindValue('id', $profissional);
    $marked = $stmt->execute();
    $m = $stmt->fetchAll(PDO::FETCH_OBJ);

    //converte a hora para minutos para poder somar o tempo de atendimento e realizar a montagem dos horarios baseado no tempo de atendimento
    $hinicial = h2m($m[0]->horainicial);
    $hfinal = h2m($m[0]->horafinal);

    $tempo = $m[0]->tempoconsulta;
    $contador = $hinicial;
    //echo $contador;
    $horarios = array();
    //$m = array();
    $horariosLivres = array();
    $i = 0;
    //lista os horarios do profissional baseados no cadastro dele
    while ($contador <= $hfinal) {
        $horarios[$i] = $datemarket.' '.m2h($contador);
        ++$i;
        $contador += $tempo;
    }

    //pega os horarios ocupados
    $date = date_create(formatar($params->data));
    $datemarket = date_format($date, 'Y-m-d');
    $datemarketinit = $datemarket.' 00:00:00';
    $datemarketfinal = $datemarket.' 23:59:59';

    $stmt = $oConexao->prepare("SELECT a.id, DATE_FORMAT(a.horainicial, '%Y-%m-%d %h:%i:%s') as horainicial, DATE_FORMAT(a.horafinal, '%Y-%m-%d %h:%i:%s') as horafinal
									FROM agendamento a
								WHERE 
									a.status <= 3 AND 
									a.idprofissional = :professional AND 
									a.horainicial >= :datainicial AND
									a.horainicial <= :datafinal ");
    $stmt->bindValue('professional', $params->professional); //idprofissional
    $stmt->bindValue('datainicial', $datemarket.' '.$m[0]->horainicial); //data inicial
    $stmt->bindValue('datafinal', $datemarket.' '.$m[0]->horafinal); //data final
    $marked = $stmt->execute();

    if ($marked) {
        $m = $stmt->fetchAll(PDO::FETCH_OBJ);
        $oConexao = null;
    }

    //faz a comparacao dos horarios com os horarios ocupados e monta a lista somente com os horarios livres, foi adicionado 1 minuto ao horario,
    //para liberar a hora final no horario, pois estava entrando como horario ocupado, mas na realidade é um horario disponivel
    for ($i = 0; $i < count($horarios); ++$i) {
        $teste = true;
        for ($j = 0; $j < count($m); ++$j) {
            $tmp = explode(' ', $horarios[$i]);
            $datatmp = $tmp[0];
            $horatmp = $tmp[1];
            $horatmp = h2m($horatmp) + 1;
            $horatmp = m2h($horatmp);
            $tmphorario = date_create($datatmp.' '.$horatmp);
            $tmpinicio = date_create($m[$j]->horainicial);
            $tmpfinal = date_create($m[$j]->horafinal);
            if ($tmphorario >= $tmpinicio && $tmphorario <= $tmpfinal) {
                $teste = false;
                break;
            }
        }
        if ($teste) {
            $horariosLivres[$i] = $horarios[$i];
        }
    }
    $hLivres = array();
    //retira a data e deixa somente o horario
    for ($i = 0; $i < count($horariosLivres); ++$i) {
        $tmp = explode(' ', $horariosLivres[$i]);
        $hLivres[$i] = $tmp[1];
    }

    $response = array_filter($hLivres);
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
