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
                <h4 class="page-title">Dashboard</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="javascript:void(0)" target="_blank" class="btn btn-danger pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Atualize seu plano</a>
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">Home</li>
                </ol>
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">
                    <div class="row row-in">
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="icon-layers"></i>
                                    <h5 class="text-muted vb">QUESTIONÁRIOS</h5> </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <span id="count-questionarios" >0</span> </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="ti-comments"></i>
                                    <h5 class="text-muted vb">PERGUNTAS</h5> </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <span id="count-perguntas">0</span> </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="icon-people"></i>
                                    <h5 class="text-muted vb">CLIENTES</h5> </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <span id="count-clientes">0</span> </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6  b-0">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="icon-user"></i>
                                    <h5 class="text-muted vb">USUÁRIOS</h5> </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div id="count-usuarios">0</div> </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-7 col-lg-9 col-sm-12 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Nova Lista de Cliente</h3>
                    <p class="text-muted">Últimos clientes adicionados </p>
                    <div class="table-responsive">
                        <table class="table" id="table-clientes">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Telefone</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-lg-3 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                                <h3 class="box-title">Números da semana</h3>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">NOVOS QUESTIONÁRIOS</h3>
                            <ul class="list-inline two-part">
                                <li><i class="icon-folder-alt text-danger"></i></li>
                                <li class="text-right"><span id="count-s-questionarios" class="counter">0</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">NOVOS CLIENTES</h3>
                            <ul class="list-inline two-part">
                                <li><i class="icon-people text-success"></i></li>
                                <li class="text-right"><span id="count-s-clientes" class="counter">0</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- end col -->
</div><!-- /.row -->

<?php require_once 'views/template/footer.php'; ?>

<!-- javascripts -->
<script type="text/javascript" src="javascripts/office/dashboard.js"></script>


<!--Style Switcher -->
<script src="assets/template/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>