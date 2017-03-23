<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//get session local browser

$uid = $_SESSION['ang_markday_uid'];
$cpf = $_SESSION['ang_markday_cpf'];
$email = $_SESSION['ang_markday_email'];

try {
    $stmt = $oConexao->prepare("SELECT a.id, a.hash, DATE_FORMAT(a.horainicial, '%d') as dia, DATE_FORMAT(a.horainicial, '%m') as mes,
									   DATE_FORMAT(a.horainicial, '%H:%i') as horainicial, DATE_FORMAT(a.horafinal, '%H:%i') as horafinal, a.status, 
									   c.nome as servico, c.hash as hashservice,
									   d.nomefantasia, d.hash as companyname, d.cep, d.logradouro, d.numero, d.complemento, d.bairro, e.sigla estadosigla, f.nome as cidade,
									   d.telefonecomercial, d.email,
									   g.nome as formapagamento,
									   h.valor as valorpago
									FROM agendamento a
									LEFT JOIN agendamento_servico b ON (b.idagendamento = a.id)
									LEFT JOIN servico c ON (c.id = b.idservico)
									LEFT JOIN estabelecimento d ON (d.id = a.idestabelecimento)
									LEFT JOIN estado e ON (e.idestado = d.idestado)
									LEFT JOIN cidade f ON (f.idcidade = d.idcidade)
									LEFT JOIN formapagamento g ON (g.id = a.idformapagamento)
									LEFT JOIN agendamento_pagamento h ON (h.idagendamento = a.id)
										WHERE
											a.idcliente = :id
											AND
											a.horainicial >= now() - INTERVAL 7 DAY
											AND
											a.horainicial <= now() + INTERVAL 7 DAY");
    $stmt->bindValue('id', $uid);
    $stmt->execute();
    $agendamento = $stmt->fetchAll(PDO::FETCH_OBJ);
    $size = $stmt->rowCount();

    if ($size >= 1) {
        echo json_encode($agendamento);
    } elseif ($size == 0) {
        echo '{ "status": "error",  "message": "Nenhum agendamento encontrado." }';
    } else {
        echo '{ "status": "error",  "message": "Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde" }';
    }

    //close connection
    $oConexao = null;
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
