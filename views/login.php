<!-- CSS -->
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<link rel="stylesheet" type="text/css" href="assets/css/login.css">

<div class="transparencia"></div>
<div class="container">
    <div class="title">
        <div class="separador"></div>
        <h1>Identificação</h1>
        <h2>Faça o seu login ou inscreva-se</h2>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="flat-form">
                <ul class="tabs">
                    <li>
                        <a href="#login">Login</a>
                    </li>
                    <li>
                        <a href="#cadastro" class="active">Inscreva-se</a>
                    </li>
                    <li>
                        <a href="#reset">Resetar Senha</a>
                    </li>
                </ul>
                <div id="login" class="form-action hide">
                    <h1>Login</h1>
                    <form name="formLogin" role="form" novalidate>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>E-mail</label>
                                            <input name="email" placeholder="E-mail" class="form-control" type="email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Senha</label>
                                            <input name="senha" placeholder="Senha" class="form-control" type="password" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="sumit" class="btn btn-success btn-fill btn-wd">ACESSAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--/#login.form-action-->
             
                <div id="cadastro" class="form-action show">
                    <h1>Dados Pessoais</h1>
                    <form id="formCadastro" name="formCadastro">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="error">
                                        <abbr>*</abbr> Campo obrigatório
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome<abbr>*</abbr></label>
                                    <input id="nome" name="nome" placeholder="Seu nome" class="form-control" type="text" maxLength="60">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sobrenome</label>
                                    <input id="sobrenome" name="sobrenome" placeholder="Sobrenome" class="form-control" type="text" maxLength="40">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome no Crachá<abbr>*</abbr></label>
                                    <input id="cracha" name="cracha" placeholder="Nome no crachá" class="form-control" type="text" maxLength="60">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>E-mail<abbr>*</abbr></label>
                                    <input id="email" name="email" placeholder="E-mail" class="form-control" type="email" maxLength="160">
                                    <div class="loading-check hidden">
                                        <img src="assets/images/common/loading.gif" width="28">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ingresso<abbr>*</abbr></label>
                                    <select id="ingresso" name="ingresso" class="form-control">
                                       <option value="" disabled selected>Lote</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Sexo<abbr>*</abbr></label>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div class="radio">
                                            <label class="col-md-6"><input id="sexo" name="sexo" value="M" type="radio"> Masculino</label>
                                            <label class="col-md-6"><input id="sexo" name="sexo" value="F" type="radio"> Feminino</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CPF<small>(Apenas número)</small><abbr>*</abbr></label>
                                    <input id="cpf" name="cpf" placeholder="CPF" class="form-control" type="text" maxLength="11">
                                    <div class="loading-check hidden">
                                        <img src="assets/images/common/loading.gif" width="28">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Possui algum tipo de deficiência?<abbr>*</abbr></label>
                                    <select id="deficiencia" name="deficiencia" class="form-control">
                                        <option value="" disabled selected>Deficiência</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>País de Origem<abbr>*</abbr></label>
                                    <select id="pais" name="pais" class="form-control">
                                        <option value="" disabled selected>Pais</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado<abbr>*</abbr></label>
                                    <select id="estado" name="estado" class="form-control">
                                        <option value="" disabled selected>Estado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade<abbr>*</abbr></label>
                                    <select id="cidade" name="cidade" class="form-control">
                                        <option value="" disabled selected>Cidade</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Senha<abbr>*</abbr></label>
                                    <input id="senha" name="senha" placeholder="Senha" class="form-control" type="password" maxLength="255">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmar Senha<abbr>*</abbr></label>
                                    <input id="confirmasenha" name="confirmasenha" placeholder="Confirmar Senha" class="form-control" type="password" maxLength="255">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <!-- <button ng-click="save()" 
                                ng-disabled="formRegister.$invalid || status.loading || usuario.senha != usuario.confirmasenha || cpf.found || email.found" 
                                type="sumit" 
                                class="btn btn-success btn-fill btn-wd"
                                ng-switch="status.loading">
                                    <span ng-switch-default>CADASTRAR</span>
                                    <span ng-switch-when="true">AGUARDE...</span>
                                </button> -->
                                <button id="salvar" class="btn btn-success btn-fill btn-wd">
                                    <span>CADASTRAR</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--/#cadastro.form-action-->

                <div id="reset" class="form-action hide">
                    <h1>Nova Senha</h1>
                    <form name="formReset" role="form" novalidate>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input name="email" placeholder="E-mail" class="form-control" type="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-success btn-fill btn-wd" type="sumit">ENVIAR</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--/#register.form-action-->
            </div>

        </div>
    </div>
</div>

<!--.modal -->
<div id="modal-pagamento" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-loading">
              <div class="col-md-12 text-center">
                <div class="loading">
                    <img src="assets/images/common/loading.gif" width="38">
                    <p>Aguarde, processando...</p>
                </div>
              </div>
            </div><!-- /.modal-loading -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- javascripts -->
<script type="text/javascript" src="assets/javascript/jquery.payform.min.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/javascript/validate/cpfBR.js"></script>
<script type="text/javascript" src="javascripts/vendor/functions.js"></script>
<script type="text/javascript" src="javascripts/vendor/login.js"></script>

<!-- Javascript Pagseguro -->
<script type="text/javascript" src="<?=javascriptURL?>"></script>