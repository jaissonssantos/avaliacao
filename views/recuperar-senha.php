<!-- css -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="assets/css/login.css">

<a href="/login" class="voltar">Voltar</a>
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
                    <form id="formRecuperar" name="formRecuperar" class="form-signin">
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

                            <button id="acessar" 
                                class="btn btn-lg btn-login btn-acessar btn-block">
                                    <span>RECUPERAR</span>
                            </button>
                        </section>
                    </form>
                    <a href="/empresa" 
                        class="footer-link">&copy; <?=date('Y')?> SuperCRM.me &trade; </a>
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