<!-- css -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="assets/css/questionario.css">

<!-- wrapper -->
<div class="wrapper">

    <!--.main-->
    <div class="hero-1">
        <div class="row">
            <div class="main">
                <div class="col-md-12">
                    <div class="site-logo">
                        <a href="/"></a>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/.hero-1-->

    <div class="row">
        <!--.main-->
        <div class="main">
            <form id="forms" class="forms">
                <div class="view">
                    <div id="loading" class="text-center">
                        <img src="assets/images/loading.gif">
                        <p>Aguarde um pouco, estamos processando...</p>
                    </div>

                    <div id="title" class="col-md-12 hidden">
                        <h3>Formulário sem título</h3>
                        <p>Preencha os dados abaixo</p>
                        <div class="divisor"></div>
                    </div>

                    <div id="error" class="row hidden">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <p></p>
                            </div>
                        </div>
                    </div>

                    <div class="success hidden">
                        <div class="col-md-12">
                            <div class="icon">
                                <img src="assets/icons/icon_success.svg">
                            </div>
                            <p>Sua resposta foi registrada.</p>
                            <a href="forms/<?=$url_subpath?>">Enviar outra resposta</a>
                        </div>
                    </div>

                    <!-- hidden input -->
                    <input type="hidden" id="hash" name="hash" value="<?=$url_subpath?>">

                    <div class="items hidden">
                    </div><!--/.items-->

                    <div id="send" class="col-md-12 hidden">
                        <div class="form-group">
                            <button type="submit" id="enviar" 
                                class="btn btn-enviar btn-success">Enviar</button>
                        </div>
                        <p class="obs">Jamais envie senhas pelo formulário.</p>
                    </div>

                </div><!--/.view-->
            </form>
        </div><!--/.main-->
    </div><!--/.row-->

    <div class="row">
        <!--.main-->
        <div class="main footer">
                <div class="text-center">
                    <p>Este conteúdo não foi criado nem aprovado pelo SuperCRM. <a href="/abuso">Denuncie abuso</a>.</p>
                </div>
            </div>
        </div><!--/.main-->
    </div><!--/.row-->

</div><!--/.wrapper-->

<!-- javascripts -->
<script type="text/javascript" src="assets/javascript/jquery.validate.min.js"></script>
<script type="text/javascript" src="javascripts/questionario.js"></script>