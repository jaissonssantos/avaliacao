<?php


use Utils\Conexao;

$oConexao = Conexao::getInstance();

//get session local browser
$uid = $_SESSION['ang_markday_uid'];
$cpf = $_SESSION['ang_markday_cpf'];
$email = $_SESSION['ang_markday_email'];

//params
$params = json_decode(file_get_contents('php://input'));

//phpmailer
include_once BASE_DIR.'/utils/phpmailer/class.phpmailer.php';
include_once BASE_DIR.'/utils/phpmailer/class.smtp.php';

try {
    if ($params) {
        $stmt = $oConexao->prepare('UPDATE agendamento SET status = :status WHERE id = :hash AND idcliente = :uid');
        $stmt->bindValue('status', 5); //status(5 - cancelado)
        $stmt->bindValue('hash', $params->hash);
        $stmt->bindValue('uid', $uid);
        $agendamento = $stmt->execute();

        if ($agendamento) {

            //details scheduling
            $stmt = $oConexao->prepare('SELECT a.horainicial, a.horafinal, a.idestabelecimento, a.idprofissional, a.idformapagamento, b.nomefantasia, b.email, b.telefonecomercial, b.cep, 
										   b.logradouro, b.numero, b.complemento, b.bairro, c.sigla as estado, d.nome as cidade,
										   e.nome as profissional, e.email as pemail, g.nome as servico, g.valor, g.promocao, g.valorpromocao,
										   h.nome as formapagamento,
										   i.nome as cnome, i.email as cemail
										FROM agendamento a
										LEFT JOIN estabelecimento b ON(a.idestabelecimento = b.id)
										LEFT JOIN estado c ON(b.idestado = c.idestado)
										LEFT JOIN cidade d ON(b.idcidade = d.idcidade)
										LEFT JOIN profissional e ON(a.idprofissional = e.id)
										LEFT JOIN agendamento_servico f ON(a.id = f.idagendamento)
										LEFT JOIN servico g ON(f.idservico = g.id)
										LEFT JOIN formapagamento h ON(a.idformapagamento = h.id)
										LEFT JOIN cliente i ON(a.idcliente = i.id)
											WHERE 
												a.id = :hash');
            $stmt->bindValue('hash', $params->hash);
            $stmt->execute();
            $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($details) {
                foreach ($details as $row) {
                    /*set item for send e-mail*/
                    $scemail['profissional'] = $row['profissional'];
                    $scemail['quando'] = today($row['horainicial']).', '.todayextensive($row['horainicial']);
                    $scemail['servico'] = $row['servico'];
                    $scemail['estabelecimento'] = $row['nomefantasia'];
                    if (intval($row['promocao']) == 0) {
                        $scemail['valor'] = $row['valor'];
                    } else {
                        $scemail['valor'] = $row['valorpromocao'];
                    }
                    $scemail['onde'] = $row['logradouro'].', '.$row['numero'].', '.$row['complemento'].' '.$row['bairro'].', '.$row['cidade'].', '.$row['estado'].' - '.$row['cep'];
                    $scemail['email'] = $row['cemail'];
                    $scemail['nome'] = $row['cnome'];

                    /**
                     * layout send e-mail for client itens schedulinhg.
                     *
                     * @professional
                     * @when
                     * @price
                     * @service
                     * @establishment
                     * @where
                     * @email
                     */
                    $message = sendCancelScheduling($scemail['profissional'], $scemail['quando'], $scemail['valor'], $scemail['servico'], $scemail['estabelecimento'], $scemail['onde'], $scemail['email'], $scemail['nome'], 'Cancelado');

                    if ($row['pemail'] != '') {
                        /*send e-mail for client*/
                        if (envia_email($row['pemail'], 'Agendamento cancelado', $message, 'naoresponda@markday.com.br', $row['profissional'])) {
                            ++$sendEmail;
                        }
                    }

                    if ($row['cemail'] != '') {
                        /*send e-mail for client*/
                        if (envia_email($row['cemail'], 'Agendamento cancelado', $message, 'naoresponda@markday.com.br', $row['cnome'])) {
                            ++$sendEmail;
                        }
                    }

                    if ($sendEmail >= 1) {
                        echo '{ "status": "success",  "message": "Agendamento cancelado com sucesso" }';
                    } else {
                        echo '{ "status": "success",  "message": "Agendamento cancelado com sucesso, mais não foi enviado e-mail com os detalhes da reserva cancelada aos envolvidos" }';
                    }
                }
            }
        } else {
            echo '{ "status": "error",  "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
        }

        //close connection
        $oConexao = null;
    }
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
