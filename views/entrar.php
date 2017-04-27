<a href="/" class="voltar">Voltar</a>
<!-- Login Content -->
<div class="login" ng-init="getLogin(); getCheckLoginActive();">
    <div class="transparencia"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="account-wall">
                    <section class="align-lg-center">
                        <a href="/"><div class="site-logo"></div></a>
                    </section>
                    <form name="$root.formEntrar" 
                        role="form" class="form-signin" novalidate>
                        <section>
                            <div ng-if="error" 
                                class="alert alert-warning" 
                                ng-bind="error">
                            </div>
                            <span class="help-block text-center" 
                                ng-if="formEntrar.email.$dirty && formEntrar.email.$error.required">O campo e-mail é obrigatório
                            </span>
                            <span class="help-block text-center" 
                                ng-if="formEntrar.email.$error.email">Informe um e-mail válido
                            </span>
                            <span class="help-block text-center" 
                                ng-if="formEntrar.senha.$dirty && formEntrar.senha.$error.required">O campo senha é obrigatório
                            </span>
                        </section>
                        <section>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="email"
                                    class="form-control"
                                    name="email"
                                    placeholder="E-mail"
                                    ng-model="cliente.email" 
                                    required>
                            </div>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-key"></i>
                                </div>
                                <input type="password"
                                    class="form-control" 
                                    name="senha" 
                                    placeholder="Senha" 
                                    ng-model="cliente.senha"
                                    required>
                            </div>

                            <button id="acessar" 
                                type="submit" 
                                ng-click="login()"
                                class="btn btn-lg btn-mark btn-block" 
                                ng-disabled="results.loading || formEntrar.$invalid" 
                                ng-switch="results.loading">
                                    <span ng-switch-default>ACESSAR</span>
                                    <span ng-switch-when="true">AGUARDE...</span>
                            </button>
                        </section>
                        <section class="clearfix">
                            <div class="checkbox-styled iCheck pull-left check-errado">
                                <label class="lembrar">
                                    <input type="checkbox" ng-model="cliente.lembrarme">
                                    <span>Lembrar-me</span>
                                </label>
                            </div>
                            <a href="/redefinir-senha" 
                                class="pull-right esqueceu">Esqueceu sua senha?</a>
                        </section>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <a href="/controller/marketplace/aplicacao/fblogin" 
                                    target="_self" 
                                    class="btn btn-lg btn-block btn-facebook">
                                    Entrar com Facebook
                                </a>
                            </div>
                        </div>

                        <span class="or" data-text="Ou"></span>

                        <a href="/cadastro" 
                            target="_self" 
                            class="btn btn-lg btn-inverse btn-block">
                            Nova Conta
                        </a>
                    </form>
                    <a href="/empresa" 
                        class="footer-link">&copy; 2016 Kambô Tecnologia &trade; </a>
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