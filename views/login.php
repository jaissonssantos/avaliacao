<!-- css -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="assets/css/login.css">

<a href="/" class="voltar">Voltar</a>
<!-- Login Content -->
<div class="login">
    <div class="transparencia"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="account-wall">
                    <section class="align-lg-center">
                        <a href="/"><div class="site-logo"></div></a>
                    </section>
                    <form id="formLogin" name="formLogin" class="form-signin">
                        <section id="errorLogin" class="hidden">
                            <div class="alert alert-warning">
                                <p></p>
                            </div>
                        </section>
                        <section>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="E-mail">
                            </div>

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

                            <button id="acessar" 
                                class="btn btn-lg btn-login btn-acessar btn-block">
                                    <span>ACESSAR</span>
                            </button>
                        </section>
                        <section class="clearfix">
                            <div class="checkbox-styled iCheck pull-left check-errado">
                                <label class="lembrar">
                                    <input id="lembrarme" name="lembrarme" type="checkbox">
                                    <span>Lembrar-me</span>
                                </label>
                            </div>
                        </section>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <a href="/estabelecimento" 
                                    target="_self" 
                                    class="btn btn-lg btn-block btn-nova-conta">
                                    Nova conta
                                </a>
                            </div>
                        </div>

                        <span class="or" data-text="Ou"></span>

                        <a href="/recuperar-senha" 
                            target="_self" 
                            class="btn btn-lg btn-inverse btn-block">
                            Esqueceu sua senha?
                        </a>
                    </form>
                    <a href="/empresa" 
                        class="footer-link">&copy; <?=date('Y')?> Avalia.me &trade; </a>
                </div>
                <!-- //account-wall-->

            </div>
            <!-- //col-sm-6 col-md-4 col-md-offset-4-->
        </div>
        <!-- //row-->
    </div>
    <!-- //container-->
</div>
<!-- END Login Content -->

<!-- javascripts -->
<script type="text/javascript" src="assets/javascript/jquery.validate.min.js"></script>
<script type="text/javascript" src="javascripts/login.js"></script>