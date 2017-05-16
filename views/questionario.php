<!-- css -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="assets/css/questionario.css">

<!-- wrapper -->
<div class="wrapper">

    <!--.main-->
    <div class="hero-1">
        <div class="row">
            <div class="main">
                <div class="col-md-6">
                    <div class="site-logo">
                        <a href="/"></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="users" class="users hidden">
                        <span class="hello">Olá</span>
                        <span class="name"></span>
                        <span class="email"></span>
                        <span class="logout">
                            <a id="sair" href="javascript:void(0);">Sair</a>
                        </span>
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

                    <div id="success" class="success hidden">
                        <div class="col-md-12">
                            <div class="icon">
                                <img src="assets/icons/icon_success.svg">
                            </div>
                            <p>Sua resposta foi registrada.</p>
                            <a href="questionario/<?=$url_subpath?>">Enviar outra resposta</a>
                        </div>
                    </div>

                    <!-- hidden input -->
                    <input type="hidden" id="hash" name="hash" value="<?=$url_subpath?>">

                    <div id="items" class="items hidden">
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

<!--.modal-->
<div id="login" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">CONTA</h4>
            </div>
            <div class="modal-body">
                <div id="errorModal" class="row hidden">
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <form id="formConta" name="formConta" class="form-signin">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text"
                                        id="nome"
                                        name="nome"
                                        class="form-control"
                                        placeholder="Seu nome">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cloud"></i>
                                    </div>
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="E-mail">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text"
                                        id="telefone"
                                        name="telefone"
                                        class="form-control"
                                        placeholder="Telefone">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <input type="password"
                                        id="senha"
                                        name="senha" 
                                        class="form-control" 
                                        placeholder="Senha">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <input type="password"
                                        id="confirmasenha"
                                        name="confirmasenha" 
                                        class="form-control" 
                                        placeholder="Confirmar senha">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button id="criarconta" 
                                    class="btn btn-login btn-acessar btn-block">
                                    <span>CRIAR CONTA</span>
                            </button>
                        </div>

                        <div class="col-md-12">
                            <span class="or" data-text="Ou"></span>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:void(0);" 
                                id="entrar" 
                                class="btn btn-block btn-nova-conta">
                                ACESSAR
                            </a>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:void(0);" 
                                id="recuperar" 
                                class="btn-recuperar btn-block">
                                Esqueceu sua senha?
                            </a>
                        </div>
                    </form>
                    <form id="formLogin" name="formLogin" class="form-signin hidden">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cloud"></i>
                                    </div>
                                    <input type="text"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="E-mail">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <input type="password"
                                        id="senha"
                                        name="senha" 
                                        class="form-control" 
                                        placeholder="Senha">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button id="acessar" 
                                    class="btn btn-login btn-acessar btn-block">
                                    <span>ACESSAR</span>
                            </button>
                        </div>
                        <div class="col-md-12">
                            <a href="javascript:void(0);" 
                                id="voltar" 
                                class="btn-recuperar btn-block">
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- javascripts -->
<script type="text/javascript" src="assets/javascript/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/javascript/validate/checktelefone.cliente.js"></script>
<script type="text/javascript" src="assets/javascript/validate/checkemail.cliente.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.mask.js"></script>
<script type="text/javascript" src="javascripts/questionario.js"></script>