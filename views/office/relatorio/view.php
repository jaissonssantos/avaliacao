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
<!--link href="assets/template/plugins/bower_components/css-chart/css-chart.css" rel="stylesheet">
<!--Owl carousel CSS -->
<link href="assets/template/plugins/bower_components/owl.carousel/owl.carousel.min.css" rel="stylesheet" type="text/css" />
<link href="assets/template/plugins/bower_components/owl.carousel/owl.theme.default.css" rel="stylesheet" type="text/css" />
<!-- Custom CSS -->
<link href="assets/template/css/style.min.css" rel="stylesheet">
<!-- color CSS -->
<link href="assets/template/css/colors/red.css" id="theme" rel="stylesheet">

<!-- CSS -->
<style type="text/css">
.chart {
    width: 250px;
    height: 250px;
    text-align: center;
    font-family: arial;
    font-size: 14px !important;
}
</style>

<?php require_once 'views/template/header.php'; ?>
<?php require_once 'views/template/left.php'; ?>
    
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <form id="formUsuario" name="formUsuario">
            <!-- hidden input -->
            <input type="hidden" id="idq" name="idq" value="<?=$url_params?>">
        </form>
        
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
        <!--row -->
        <div class="row" id="resultados"></div>
        <!-- row -->
    </div><!-- end col -->
</div><!-- /.row -->

<?php require_once 'views/template/footer.php'; ?>

<!-- javascripts -->
<script type="text/javascript" src="javascripts/office/relatorio/list.js"></script>

<!-- Custom Theme JavaScript -->
<script src="assets/template/js/custom.min.js"></script>

<script src="http://www.chartjs.org/dist/2.6.0/Chart.bundle.js"></script>





<!--Style Switcher -->
<script src="assets/template/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>