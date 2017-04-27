<?php
    if (!isset(
        $_SESSION['avaliacao_uid'],
        $_SESSION['avaliacao_nome'],
        $_SESSION['avaliacao_sobrenome'],
        $_SESSION['avaliacao_email'],
        $_SESSION['avaliacao_perfil'],
        $_SESSION['avaliacao_estabelecimento']
    )) {
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
<!-- Custom CSS -->
<link href="assets/template/css/style.min.css" rel="stylesheet">
<!-- color CSS -->
<link href="assets/template/css/colors/blue.css" id="theme" rel="stylesheet">

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
                <a href="" target="_blank" class="btn btn-danger pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Atualize seu plano</a>
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">Home</li>
                </ol>
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                
                    <div class="row">
                        <div class="col-lg-6">
                            <h3 class="box-title m-b-0">Basic Tooltip</h3>
                            <p class="text-muted m-b-30">Just put this to any tag <code>data-toggle="tooltip" title="Default tooltip"</code></p>
                            <div class="button-box">
                                <button type="button" class="btn btn-default btn-outline" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Tooltip on top</button>
                                <button type="button" class="btn btn-default btn-outline" data-toggle="tooltip" data-placement="right" title="" data-original-title="Tooltip on right">Tooltip on right</button>
                                <button type="button" class="btn btn-default btn-outline" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Tooltip on bottom">Tooltip on bottom</button>
                                <button type="button" class="btn btn-default btn-outline" data-toggle="tooltip" data-placement="left" title="" data-original-title="Tooltip on left">Tooltip on left</button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h3 class="box-title m-b-0">Clickable Tooltip</h3>
                            <p class="text-muted m-b-30">Four options are available: top, right, bottom, and left aligned.</p>
                            <div class="button-box">
                                <button type="button" class="btn btn-default btn-outline tooltip-success" data-toggle="tooltip" data-trigger="click" data-placement="top" title="" data-original-title="Tooltip on top">Click</button>
                                <button type="button" class="btn btn-default btn-outline tooltip-success" data-toggle="tooltip" data-trigger="click" data-placement="right" title="" data-original-title="Tooltip on right">Click</button>
                                <button type="button" class="btn btn-default btn-outline tooltip-success" data-toggle="tooltip" data-trigger="click" data-placement="bottom" title="" data-original-title="Tooltip on bottom">Click</button>
                                <button type="button" class="btn btn-default btn-outline tooltip-success" data-toggle="tooltip" data-trigger="click" data-placement="left" title="" data-original-title="Tooltip on left">Click</button>
                            </div>
                        </div>
                    </div><!-- /.row -->

                </div>
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->

    </div><!-- end col -->
</div><!-- /.row -->

<?php require_once 'views/template/footer.php'; ?>

<!-- javascripts -->
<!-- <script type="text/javascript" src="javascripts/vendor/login.js"></script> -->
