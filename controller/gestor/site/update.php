<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//get session local browser
$uid = $_SESSION['ang_plataforma_uid'];
$idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];

//params
$exibirintroducao = $_POST['exibirintroducao'] == 'true' ? 1 : 0;
$introducao = $_POST['introducao'];
$tituloempresa = $_POST['tituloempresa'];
$sobre = $_POST['sobre'];
$tituloprofissional = $_POST['tituloprofissional'];
$redes = $_POST['redesocial'];
$profissionais = $_POST['profissionais'];
$servicos = $_POST['servicos'];

//return JSON
$response = new stdClass();

try {
    if (isset($uid)) {
        $upload = false;
        $uploaderrors = 0;
        //updata image client
        if (isset($_FILES[ 'file' ])) {
            $temp = '../../assets/img/background';
            if (!file_exists($temp)) {
                mkdir($temp);
            }
            $tmpfile = $_FILES[ 'file' ][ 'tmp_name' ];
            $extension = strtolower(end(explode('.', $_FILES[ 'file' ][ 'name' ])));
            $newnamefile = md5(uniqid(time())).'.'.$extension;
            $uploadpath = $temp.DIRECTORY_SEPARATOR.$newnamefile;

            if (move_uploaded_file($tmpfile, $uploadpath)) {

                //delete image in folder
                $stmt = $oConexao->prepare('SELECT count(id) as total, background as image
										FROM estabelecimento_page
											WHERE
												idestabelecimento = :idestabelecimento');
                $stmt->bindValue('idestabelecimento', $idestabelecimento);
                $stmt->execute();
                $item = $stmt->fetchAll(PDO::FETCH_ASSOC);
                unlink($temp.DIRECTORY_SEPARATOR.$item[0]['image']);

                //update image
                $stmt = $oConexao->prepare('UPDATE estabelecimento_page SET background = :image WHERE idestabelecimento = :idestabelecimento');
                $stmt->bindValue('image', $newnamefile);
                $stmt->bindValue('idestabelecimento', $idestabelecimento);
                $pageUpload = $stmt->execute();
                $upload = true;
            } else {
                $upload = true;
                ++$uploaderrors;
            }
        } else {
            $upload = true;
        }

        $stmtpage = $oConexao->prepare('SELECT id, template FROM estabelecimento_page WHERE idestabelecimento = :idestabelecimento');
        $stmtpage->bindValue('idestabelecimento', $idestabelecimento);
        $stmtpage->execute();
        $pageinfo = $stmtpage->fetchObject();

        $stmt = $oConexao->prepare('UPDATE estabelecimento_page SET exibirintroducao = :exibirintroducao, introducao = :introducao, tituloempresa = :tituloempresa, sobre = :sobre, tituloprofissional = :tituloprofissional WHERE idestabelecimento = :idestabelecimento');
        $stmt->bindValue('exibirintroducao', $exibirintroducao);
        $stmt->bindValue('introducao', $introducao);
        $stmt->bindValue('tituloempresa', $tituloempresa);
        $stmt->bindValue('sobre', $sobre);
        $stmt->bindValue('tituloprofissional', $tituloprofissional);
        $stmt->bindValue('idestabelecimento', $idestabelecimento);
        $pageSettings = $stmt->execute();

        //salvar as redes sociais
        //excluir para salvar as atualizadas
        $stmt = $oConexao->prepare('DELETE FROM estabelecimento_redesocial WHERE idestabelecimento = :idestabelecimento');
        $stmt->bindValue('idestabelecimento', $idestabelecimento);
        $deleters = $stmt->execute();

        //se tiver deletado os registros
        if ($deleters) {
            //insere as novas redes sociais
            for ($i = 0; $i < count($redes); ++$i) {
                $stmt = $oConexao->prepare('INSERT INTO estabelecimento_redesocial (idestabelecimento, tipo, url) VALUES (:idestabelecimento, :tipo, :url)');
                $stmt->bindValue('idestabelecimento', $idestabelecimento);
                $stmt->bindValue('tipo', $redes[$i]['tipo']);
                $stmt->bindValue('url', $redes[$i]['url']);
                $redesociais = $stmt->execute();
            }
        }

        //salvar os profissionais
        //excluir para salvar as atualizadas
        $stmt = $oConexao->prepare('DELETE FROM page_profissional WHERE idestabelecimento_page = :id');
        $stmt->bindValue('id', $pageinfo->id);
        $deletepp = $stmt->execute();

        //se tiver deletado os registros
        if ($deletepp) {
            //insere as novas redes sociais
            for ($i = 0; $i < count($profissionais); ++$i) {
                if ($profissionais[$i]['selected'] == 'true') {
                    $stmt = $oConexao->prepare('INSERT INTO page_profissional (ativo, idprofissional, idestabelecimento_page) VALUES (1, :idprofissional, :idestabelecimento_page)');
                    $stmt->bindValue('idprofissional', $profissionais[$i]['id']);
                    $stmt->bindValue('idestabelecimento_page', $pageinfo->id);
                    $pps = $stmt->execute();
                }
            }
        }

        // //salvar os servicos
        //excluir para salvar as atualizadas
        $stmt = $oConexao->prepare('DELETE FROM page_servico WHERE idestabelecimento_page = :id');
        $stmt->bindValue('id', $pageinfo->id);
        $deletesp = $stmt->execute();

        //se tiver deletado os registros
        if ($deletesp) {
            //insere as novas redes sociais
            for ($i = 0; $i < count($servicos); ++$i) {
                if ($servicos[$i]['selected'] == 'true') {
                    $stmt = $oConexao->prepare('INSERT INTO page_servico (ativo, idservico, idestabelecimento_page) VALUES (1, :idservico, :idestabelecimento_page)');
                    $stmt->bindValue('idservico', $servicos[$i]['id']);
                    $stmt->bindValue('idestabelecimento_page', $pageinfo->id);
                    $sps = $stmt->execute();
                }
            }
        }

        if ($pageSettings && $redesociais && $pps && $sps && $upload && $uploaderrors == 0) {
            $response->status = 'success';
            $response->message = 'Atualizado com sucesso';
        } else {
            $response->status = 'error';
            $response->message = 'Ops! tivemos um instabilidade em nossos servidores, faça uma nova tentativa mais tarde';
        }

        //close connection
        $oConexao = null;
    } else {
        $response->status = 'error';
        $response->message = 'Ops! faça o login novamente para executar a operação';
    }

    echo json_encode($response);
} catch (PDOException $e) {
    $oConexao->rollBack();
    echo '{ "status": "error",  "message": "'.$e->getMessage().'" }';
    die();
}
