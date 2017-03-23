<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

try {
    $stmt = $oConexao->prepare(
        'SELECT p.id, p.template, p.background, p.exibirintroducao , p.introducao, p.tituloempresa, p.sobre, p.tituloprofissional, p.idestabelecimento
										FROM estabelecimento_page p
										WHERE p.idestabelecimento = :id'
    );
    $stmt->bindValue('id', $_SESSION['ang_plataforma_estabelecimento']); //idestabelecimento
    $stmt->execute();
    $site = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($site) {
        foreach ($site as $row) {
            $website['template'] = $row['template'];
            $website['background'] = $row['background'];
            $website['exibirintroducao'] = $row['exibirintroducao'] == 1 ? true : false;
            $website['introducao'] = $row['introducao'];
            $website['tituloempresa'] = $row['tituloempresa'];
            $website['sobre'] = $row['sobre'];
            $website['tituloprofissional'] = $row['tituloprofissional'];
        }
    }

    $oConexao = null;
    echo json_encode($website);
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{"error":{"text":'.$e->getMessage().'}}';
    die();
}
