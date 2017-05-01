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
                                    <h3 class="counter text-right m-t-15 text-danger">23</h3> </div>
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
                                    <h3 class="counter text-right m-t-15 text-info">169</h3> </div>
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
                                    <h3 class="counter text-right m-t-15 text-success">431</h3> </div>
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
                                    <h3 class="counter text-right m-t-15 text-warning">157</h3> </div>
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
                    <h3 class="box-title">Yearly Sales </h3>
                    <ul class="list-inline text-right">
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #00bfc7;"></i>iPhone</h5> </li>
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #fdc006;"></i>iPad</h5> </li>
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #9675ce;"></i>iPod</h5> </li>
                    </ul>
                    <div id="morris-area-chart" style="height: 340px;"></div>
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
                                <li class="text-right"><span class="counter">7</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">NOVOS CLIENTES</h3>
                            <ul class="list-inline two-part">
                                <li><i class="icon-people text-success"></i></li>
                                <li class="text-right"><span class="counter">23</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-3 col-xs-12 col-sm-6">
                <div class="white-box m-b-0 bg-danger">
                    <h3 class="text-white box-title">Analysis <span class="pull-right"><i class="fa fa-caret-up"></i> 260</span></h3>
                    <div id="sparkline1dash"><canvas style="display: inline-block; width: 298px; height: 70px; vertical-align: top;" width="298" height="70"></canvas></div>
                </div>
                <div class="white-box">
                    <div class="row">
                        <div class="pull-left">
                            <div class="text-muted m-t-20">Site Analysis</div>
                            <h2>21000</h2> </div>
                        <div data-label="60%" class="css-bar css-bar-60 css-bar-lg m-b-0 css-bar-danger pull-right"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-12 col-sm-6">
                <div class="white-box m-b-0 bg-info">
                    <h3 class="text-white box-title">Sales <span class="pull-right"><i class="fa fa-caret-down"></i> 160</span></h3>
                    <div id="sparkline2dash" class="text-center"><canvas width="215" height="70" style="display: inline-block; width: 215px; height: 70px; vertical-align: top;"></canvas></div>
                </div>
                <div class="white-box">
                    <div class="row">
                        <div class="pull-left">
                            <div class="text-muted m-t-20">Total Sales</div>
                            <h2>21000</h2> </div>
                        <div data-label="60%" class="css-bar css-bar-60 css-bar-lg m-b-0  css-bar-info pull-right"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-12 col-sm-6">
                <div class="white-box m-b-0 bg-purple">
                    <h3 class="text-white box-title">Site visits <span class="pull-right"><i class="fa fa-caret-up"></i> 260</span></h3>
                    <div id="sparkline3dash"><canvas style="display: inline-block; width: 298px; height: 70px; vertical-align: top;" width="298" height="70"></canvas></div>
                </div>
                <div class="white-box">
                    <div class="row">
                        <div class="pull-left">
                            <div class="text-muted m-t-20">Total Visits</div>
                            <h2>26000</h2> </div>
                        <div data-label="60%" class="css-bar css-bar-60 css-bar-lg m-b-0 css-bar-purple pull-right"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-12 col-sm-6">
                <div class="white-box m-b-0 bg-inverse">
                    <h3 class="text-white box-title">Power consumption <span class="pull-right"><i class="fa fa-caret-up"></i> 260</span></h3>
                    <div id="sparkline4dash" class="text-center"><canvas width="215" height="70" style="display: inline-block; width: 215px; height: 70px; vertical-align: top;"></canvas></div>
                </div>
                <div class="white-box">
                    <div class="row">
                        <div class="pull-left">
                            <div class="text-muted m-t-20">Total Usage</div>
                            <h2>61000</h2> </div>
                        <div data-label="60%" class="css-bar css-bar-60 css-bar-lg m-b-0 css-bar-inverse pull-right"></div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-8 col-lg-9 col-sm-6 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">SALES ANALYTICS</h3>
                    <ul class="list-inline text-center">
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #00bfc7;"></i>Site A View</h5> </li>
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #fdc006;"></i>Site B View</h5> </li>
                    </ul>
                    <div id="morris-area-chart2" style="height: 370px;"></div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Total Sites Visit</h3>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6  m-t-30">
                                    <h1 class="text-warning">6778</h1>
                                    <p class="text-muted">APRIL 2017</p> <b>(150-165 Sales)</b> </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div id="sales1" class="text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Sales Difference</h3>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6  m-t-30">
                                    <h1 class="text-info">$2478</h1>
                                    <p class="text-muted">APRIL 2017</p> <b>(150-165 Sales)</b> </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div id="sales2" class="text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->

        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Nova Lista de Cliente</h3>
                    <p class="text-muted">Últimos clientes adicionados </p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Telefone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Deshmukh</td>
                                    <td>@Genelia</td>
                                    <td><span class="label label-danger">Fever</span> </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Deshmukh</td>
                                    <td>@Ritesh</td>
                                    <td><span class="label label-info">Cancer</span> </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Sanghani</td>
                                    <td>@Govinda</td>
                                    <td><span class="label label-warning">Lakva</span> </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Roshan</td>
                                    <td>@Hritik</td>
                                    <td><span class="label label-success">Dental</span> </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Joshi</td>
                                    <td>@Maruti</td>
                                    <td><span class="label label-info">Cancer</span> </td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Nigam</td>
                                    <td>@Sonu</td>
                                    <td><span class="label label-success">Dental</span> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->

    </div><!-- end col -->
</div><!-- /.row -->

<?php require_once 'views/template/footer.php'; ?>

<!-- javascripts -->
<script type="text/javascript" src="javascripts/vendor/office/dashboard.js"></script>

<!--Morris JavaScript -->
<script src="assets/template/plugins/bower_components/raphael/raphael-min.js"></script>
<script src="assets/template/plugins/bower_components/morrisjs/morris.js"></script>

<!-- Sparkline chart JavaScript -->
<script src="assets/template/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="assets/template/plugins/bower_components/jquery-sparkline/jquery.charts-sparkline.js"></script>

<!--Counter js -->
<script src="assets/template/plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
<script src="assets/template/plugins/bower_components/counterup/jquery.counterup.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="assets/template/js/custom.min.js"></script>
<script src="assets/template/js/widget.js"></script>

<!--Style Switcher -->
<script src="assets/template/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>