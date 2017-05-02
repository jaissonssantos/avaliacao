<?php
    if (!isset(
        $_SESSION['avaliacao_uid'],
        $_SESSION['avaliacao_nome'],
        $_SESSION['avaliacao_sobrenome'],
        $_SESSION['avaliacao_email'],
        $_SESSION['avaliacao_perfil'],
        $_SESSION['avaliacao_gestor'],
        $_SESSION['avaliacao_estabelecimento']
    ) || $_SESSION['avaliacao_gestor'] == 1) {
        header('Location: /login');
    }
?>
<!-- Bootstrap Core CSS -->
<link href="assets/template/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/template/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
<!-- animation CSS -->
<link href="assets/template/css/animate.css" rel="stylesheet">
<!-- Menu CSS -->
<link href="assets/template/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
<!-- morris CSS -->
<link href="assets/template/plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
<link href="assets/template/plugins/bower_components/css-chart/css-chart.css" rel="stylesheet">
<!--Owl carousel CSS -->
<link href="assets/template/plugins/bower_components/owl.carousel/owl.carousel.min.css" rel="stylesheet" type="text/css" />
<link href="assets/template/plugins/bower_components/owl.carousel/owl.theme.default.css" rel="stylesheet" type="text/css" />
<!-- Custom CSS -->
<link href="assets/template/css/style.min.css" rel="stylesheet">
<!-- color CSS -->
<link href="assets/template/css/colors/red.css" id="theme" rel="stylesheet">

<?php require_once 'views/template/header.php'; ?>
<?php require_once 'views/template/left.php'; ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Usuários</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="javascript:void(0)" target="_blank" class="btn btn-danger pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Atualize seu plano</a>
                <ol class="breadcrumb">
                    <li><a href="/office/dashboard">Dashboard</a></li>
                    <li><a href="/office/usuario">Usuários</a></li>
                    <li class="active">Redefinir senha</li>
                </ol>
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-12">                
                <div class="white-box">

                    <div id="form-loading" class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <img src="assets/images/loading.gif">
                                <p>Aguarde um pouco, estamos processando...</p>
                            </div>
                        </div>
                    </div>

                    <div id="form" class="row hidden">

                        <div class="col-sm-12 col-xs-12">
                            <h3 class="box-title m-b-0">Redefinir senha do usuário: <span id="editName"></span></h3>
                            <p class="text-muted m-b-30 font-13"> Redefinição da senha atual </p>
                        </div>

                        <div class="col-sm-12 col-xs-12">
                            <form id="formUsuario" name="formUsuario">
                                
                                <div id="error" class="row hidden">
                                    <div class="col-md-12">
                                        <div class="alert alert-warning">
                                            <p></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- hidden input -->
                                <input type="hidden" id="id" name="id" value="<?=$url_params?>">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="senha">Senha:</label>
                                            <input type="password" class="form-control" id="senha" name="senha"> 
                                        </div>                                
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="confirmasenha">Confirme a senha</label>
                                            <input type="password" class="form-control" id="confirmasenha" name="confirmasenha"> 
                                        </div>                                
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" id="salvar" 
                                            class="btn btn-success waves-effect waves-light m-r-10">Salvar</button>
                                        <button type="button" id="cancelar" 
                                            class="btn btn-inverse waves-effect waves-light">Voltar</button>
                                    </div>
                                </div>

                            </form><!--/form-->
                        </div><!-- /.col-sm-12 -->

                    </div><!-- /.row -->

                </div><!--/.white-box-->
            </div>
        </div><!--/.row -->


    </div><!-- end col -->
</div><!-- /.row -->

<?php require_once 'views/template/footer.php'; ?>

<!-- javascripts -->
<script type="text/javascript" src="assets/javascript/jquery.validate.min.js"></script>
<script type="text/javascript" src="javascripts/functions.js"></script>
<script type="text/javascript" src="javascripts/office/usuario/password.js"></script>