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
<!-- Bootstrap Select -->
<link href="assets/template/plugins/bower_components/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<!-- Switchery -->
<link href="assets/template/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
<!-- Page plugins css -->
<link href="assets/template/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
<!-- Date picker plugins css -->
<link href="assets/template/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
<!-- Daterange picker plugins css -->
<link href="assets/template/plugins/bower_components/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<link href="assets/template/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
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
                <h4 class="page-title">Questionários</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="javascript:void(0)" target="_blank" class="btn btn-danger pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Atualize seu plano</a>
                <ol class="breadcrumb">
                    <li><a href="/office/dashboard">Dashboard</a></li>
                    <li><a href="/office/questionario">Questionários</a></li>
                    <li class="active">Editar</li>
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
                            <h3 class="box-title m-b-0">Edição do questionário: <span id="editName"></span></h3>
                            <p class="text-muted m-b-30 font-13"> Formulário de edição </p>
                        </div>

                        <div class="col-sm-12 col-xs-12">
                            <form id="formQuestionario" name="formQuestionario">

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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="titulo">Título do formulário</label>
                                            <input type="text" class="form-control" id="titulo" name="titulo"> 
                                        </div>                                
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="introducao">Descrição do formulário</label>
                                            <input type="text" class="form-control" id="introducao" name="introducao"> 
                                        </div>                                
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="introducao">Prazo de encerramento</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="prazodata" name="prazodata" 
                                                placeholder="DD/MM/AAAA" value="<?=date('d/m/Y', strtotime("+90 days"))?>">
                                                <span class="input-group-addon">
                                                    <i class="icon-calender"></i>
                                                </span> 
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group clockpicker m-t-25 m-b-20" 
                                            data-placement="bottom" data-align="top" data-autoclose="true">
                                            <input type="text" class="form-control" id="prazohora" name="prazohora" value="00:00"> 
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span> 
                                                </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="add" class="btn btn-info btn-sm m-b-20 waves-effect waves-light" type="button">
                                            <span class="btn-label"><i class="fa fa-plus"></i></span>Adicionar pergunta
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div id="perguntas" class="col-md-12">
                                        <div id="pergunta" class="well" data-id="1">
                                            <input type="hidden" id="perguntaId" 
                                                name="perguntaId[]">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" 
                                                        id="pergunta" name="pergunta1" placeholder="Pergunta"> 
                                                    </div>                                
                                                </div>
                                                <div id="tipos" class="col-md-3">
                                                    <select id="tipo" name="tipo[]" class="form-control">
                                                        <option value="1"> Resposta curta</option>
                                                        <option value="2"> Múltipla escolha</option>
                                                        <option value="3"> Caixas de seleção</option>
                                                    </select>
                                                </div>
                                                <div id="respostas" class="col-md-12">
                                                    <div id="campo" class="form-group">
                                                        <input type="text" class="form-control" 
                                                        id="resposta1" name="resposta1[]" placeholder="Texto da resposta curta" 
                                                        disabled="true"> 
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <span>Obrigatória</span>
                                                    <input type="checkbox" id="obrigatoria" name="obrigatoria1" value="1" />

                                                    <div id="loading-excluir" class="pull-right hidden">
                                                        <img src="assets/images/loading.gif" width="24">
                                                    </div>

                                                    <button id="pergunta-excluir" 
                                                        class="btn btn-danger btn-sm m-b-0 pull-right hidden" 
                                                        type="button">
                                                        <span class="btn-label"><i class="ti-trash"></i></span>Excluir
                                                    </button>
                                                    
                                                    <button id="pergunta-duplicar" 
                                                        class="btn btn-inverse btn-sm m-b-0 pull-right m-r-10 hidden" 
                                                        type="button">
                                                        <span class="btn-label"><i class="ti-files"></i></span>Duplicar
                                                    </button>
                                                </div>
                                            </div>
                                        </div><!--/.well-->
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

<!--Bootstrap Select -->
<script src="assets/template/plugins/bower_components/bootstrap-select/bootstrap-select.min.js"></script>
<!--Switchery-->
<script src="assets/template/plugins/bower_components/switchery/dist/switchery.min.js"></script>
<!-- Clock Plugin JavaScript -->
<script src="assets/template/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js"></script>
<!-- Color Picker Plugin JavaScript -->
<script src="assets/template/plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js"></script>
<script src="assets/template/plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js"></script>
<script src="assets/template/plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
<!-- Date Picker Plugin JavaScript -->
<script src="assets/template/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="assets/template/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.pt-BR.js"></script>

<!-- javascripts -->
<script type="text/javascript" src="assets/javascript/jquery.validate.min.js"></script>
<script type="text/javascript" src="javascripts/functions.js"></script>
<script type="text/javascript" src="javascripts/office/questionario/edit.js"></script>