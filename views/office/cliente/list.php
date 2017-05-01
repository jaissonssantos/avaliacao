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
                <h4 class="page-title">Clientes</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="javascript:void(0)" target="_blank" class="btn btn-danger pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Atualize seu plano</a>
                <ol class="breadcrumb">
                    <li><a href="/office/dashboard">Dashboard</a></li>
                    <li><a href="/office/cliente">Clientes</a></li>
                    <li class="active">Lista</li>
                </ol>
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-12">                
                <div class="white-box">

                    <div id="col-search" class="col-md-4 hidden">
                      <form>
                        <div class="form-group">
                            <div class="input-group">
                                <input id="search" type="text" 
                                class="form-control" placeholder="Busca">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>
                      </form>
                    </div>

                    <div id="col-reload" class="col-md-8 hidden">
                        <div class="text-right">
                            <img src="assets/images/loading.gif">
                            <span>Processando...</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div id="table-loading" class="text-center">
                            <img src="assets/images/loading.gif">
                            <p>Aguarde um pouco, estamos processando...</p>
                        </div>
                        <table id="table-results" class="table hidden">
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

                    <div id="col-total" class="row hidden">
                        <div class="col-md-6">
                          <nav aria-label="Page navigation">
                              <ul id="pagination" class="pagination pagination-sm">
                                <li>
                                  <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">Primeiro</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">Voltar</span>
                                  </a>
                                </li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li>
                                  <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">Próximo</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="#" aria-label="Next">
                                    <span aria-hidden="true">Último</span>
                                  </a>
                                </li>
                              </ul>
                            </nav>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right m-t-30">
                                <span id="pagination-length"></span>
                            </div>
                        </div>
                    </div><!--/.row -->

                    <div id="col-note" class="row hidden">
                        <div class="col-md-12">
                            <p class="text-success text-left p-t-10">
                                <i class="fa fa-lock"></i>
                                <span>Clientes/Respondentes vinculado ao seu estabelecimento</span>
                            </p>
                        </div>
                    </div>

                </div><!--/.white-box-->
            </div>
        </div><!--/.row -->


    </div><!-- end col -->
</div><!-- /.row -->

<?php require_once 'views/template/footer.php'; ?>

<!-- javascripts -->
<script type="text/javascript" src="javascripts/vendor/functions.js"></script>
<script type="text/javascript" src="javascripts/vendor/office/cliente/list.js"></script>