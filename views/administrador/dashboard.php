<?php
    if (!isset(
        $_SESSION['avaliacao_uid'],
        $_SESSION['avaliacao_nome'],
        $_SESSION['avaliacao_sobrenome'],
        $_SESSION['avaliacao_email'],
        $_SESSION['avaliacao_perfil'],
        $_SESSION['avaliacao_gestor'],
        $_SESSION['avaliacao_estabelecimento']
    ) || $_SESSION['avaliacao_gestor'] == 0) {
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
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">Home</li>
                </ol>
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats"> <i class="icon-directions bg-megna"></i>
                        <div class="bodystate">
                            <h4>370</h4> <span class="text-muted">Estabelecimentos</span> </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats"> <i class="icon-people bg-info"></i>
                        <div class="bodystate">
                            <h4>342</h4> <span class="text-muted">Clientes</span> </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats"> <i class="icon-layers bg-success"></i>
                        <div class="bodystate">
                            <h4>13</h4> <span class="text-muted">Questionários</span> </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats"> <i class="icon-user bg-inverse"></i>
                        <div class="bodystate">
                            <h4>12</h4> <span class="text-muted">Usuários</span> </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->

        <div class="row">
            <div class="col-sm-6">
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
            <div class="col-sm-6">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Nova Lista de Estabelecimento</h3>
                    <p class="text-muted">Últimos estabelecimento adicionados</p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Evolução</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Genelia Deshmukh</td>
                                    <td>89.477.298/0001-39</td>
                                    <td><span class="text-danger text-semibold"><i class="fa fa-level-down" aria-hidden="true"></i> 28.76%</span> </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Ajay Devgan</td>
                                    <td>80.726.844/0001-53</td>
                                    <td><span class="text-warning text-semibold"><i class="fa fa-level-down" aria-hidden="true"></i> 8.55%</span> </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Hrithik Roshan</td>
                                    <td>30.369.719/0001-65</td>
                                    <td><span class="text-success text-semibold"><i class="fa fa-level-up" aria-hidden="true"></i> 58.56%</span> </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Steve Gection</td>
                                    <td>45.743.230/0001-89</td>
                                    <td><span class="text-info text-semibold"><i class="fa fa-level-up" aria-hidden="true"></i> 35.76%</span> </td>
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
<!-- <script type="text/javascript" src="javascripts/login.js"></script> -->
