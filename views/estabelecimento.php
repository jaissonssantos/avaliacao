<!-- css -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="assets/css/estabelecimento.css">

<div class="transparencia"></div>
<div class="fundo-estabelecimento">
	<a href="/" class="voltar">Voltar</a>
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div class="list-itens">
					<h1>Mais facilidades para criação de suas pesquisas.</h1>
					<ul>
						<li><i class="fa fa-check"></i> Pesquisas, respondentes e perguntas ilimitadas</li>
						<li><i class="fa fa-check"></i> Mensalidade de R$14,90 <small>(não cobramos taxas adicionais por usuários)</small></li>
						<li><i class="fa fa-check"></i> Coleta rápida de respostas por e-mail e rede socias</li>
						<li><i class="fa fa-check"></i> Analise de resultados<small>(Word, PDF, Excel e Power Point)</small></li>
						<li><i class="fa fa-check"></i> Relatórios e resultados em tempo real</li>

					</ul>
				</div>
			</div>
			<div class="col-md-5">
				<div id="step-1" class="register-company">
					<h2>Experimente de forma gratuito por 15 dias</h2>
					<p>Parte 1 - Dados do Administrador</p>
					<form id="formUsuario" name="formUsuario" class="form-signin">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="nome" 
										name="nome" 
										placeholder="Nome" 
										type="text">
										<div class="input-group-addon">
											<i class="fa fa-user-o"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="sobrenome" 
										name="sobrenome" 
										placeholder="Sobrenome" 
										type="text">
										<div class="input-group-addon">
											<i class="fa fa-user-o"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										type="email"
										id="email" 
										name="email" 
										placeholder="E-mail" >
										<div class="input-group-addon">
											<i class="fa fa-envelope-o"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="senha"
										name="senha" 
										placeholder="Senha" 
										type="password">
										<div class="input-group-addon">
											<i class="fa fa-key"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="confirmasenha" 
										name="confirmasenha" 
										placeholder="Confirmar senha"
										type="password">
										<div class="input-group-addon">
											<i class="fa fa-key"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-center">
								<button id="continuar"
									class="btn-success btn btn-avalia-lg">CONTINUAR</button>
							</div>
						</div>
					</form>
				</div>

				<div id="step-2" class="register-company register2 hidden">
					<h2>Experimente de forma gratuito por 15 dias</h2>
					<p>Parte 2 - Dados do Estabelecimento</p>
					<form id="formCadastroEstabelecimento" 
						name="formCadastroEstabelecimento" class="form-signin">
						<div id="errorCadastro" class="row hidden">
							<div class="col-md-12">
								<div class="alert alert-warning">
									<p></p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="nomefantasia"
										name="nomefantasia"
										placeholder="Nome do estabelecimento"
										type="text">
										<div class="input-group-addon">
											<i class="fa fa-building-o"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="cpfcnpj"
										name="cpfcnpj"
										placeholder="CPF/CNPJ">
										<div class="input-group-addon">
											<i class="fa fa-building-o"></i>
										</div>
									</div>
								</div>
							</div>						
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="telefone" 
										name="telefone" 
										placeholder="Telefone" 
										type="tel">
										<div class="input-group-addon">
											<i class="fa fa-mobile"></i>
										</div>
									</div>
								</div>
							</div>						
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="cep" 
										name="cep" 
										placeholder="CEP" 
										type="text">
										<div class="input-group-addon">
											<i class="fa fa-map-marker"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="select-style">
										<select id="estado" name="estado" class="form-control">
												<option value="" disabled selected>Estado</option>
											</select>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="select-style">
										<select id="cidade" name="cidade" class="form-control">
											<option value="" disabled selected>Cidade</option>
											</select>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" 
										id="endereco"
										name="endereco" 
										placeholder="Av. Brasil, 1000" type="text">
										<div class="input-group-addon">
											<i class="fa fa-map-marker"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control"
										id="bairro" name="bairro" placeholder="Bairro" type="text">
										<div class="input-group-addon">
											<i class="fa fa-map-marker"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" id="numero" name="numero" placeholder="Número" type="number">
										<div class="input-group-addon">
											<i class="fa fa-map-marker"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control"  
										id="complemento" name="complemento" placeholder="Complemento (opcional)"
										type="text">
										<div class="input-group-addon">
											<i class="fa fa-map-marker"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-center">
								<button id="voltar"
									class="btn btn-avalia-middle-left">VOLTAR</button>
								<button id="salvar"
								class="btn-success btn btn-avalia-middle-right">CADASTRAR</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- javascripts -->
<script type="text/javascript" src="assets/javascript/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.cpfcnpj.min.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.mask.js"></script>
<script type="text/javascript" src="assets/javascript/validate/checkcpfcnpj.js"></script>
<script type="text/javascript" src="assets/javascript/validate/checkemail.js"></script>
<script type="text/javascript" src="javascripts/vendor/functions.js"></script>
<script type="text/javascript" src="javascripts/vendor/estabelecimento.js"></script>