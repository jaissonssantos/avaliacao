'use strict';

var app = angular.module('app', [
		    'ng',
			'ngResource',
			'ngRoute',
			'ui.bootstrap',
		    'oc.lazyLoad',
		    'ui.utils.masks',
		    'ui.mask',
		    'idf.br-filters',
		    'ngFileUpload',
		    'ADM-dateTimePicker',
		    'ngTagsInput',
		    'textAngular'
		  ]);


/* configuration and routs */
app.config( function($routeProvider, $locationProvider){

	// use the HTML5 History API
    $locationProvider.html5Mode(true);

	$routeProvider
		.when('/',{
			templateUrl: 'views/login.html',
			title: 'app',
			controller: 'entrarController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'app/controllers/aplicacao/entrarController.js'
                    	]
                	});
				}]
			}
		})

		.when('/entrar',{
			templateUrl: 'views/login.html',
			title: 'app',
			controller: 'entrarController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'app/controllers/aplicacao/entrarController.js'
                    	]
                	});
				}]
			}
		})

		.when('/pagamento-do-plano',{
			templateUrl: 'views/plan-payment.html',
			title: 'pagamento do plano',
			controller: 'planPaymentCtrl',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'app/controllers/plataforma/planPaymentCtrl.js',
                    		'app/services/lembreteService.js',
							'assets/js/modernizr.js',
							'assets/js/main.js'
                    	]
                	});
				}]
			}
		})

		.when('/dashboard', {
			templateUrl: 'views/dashboard.html',
			title: 'dashboard',
			controller: 'dashboardController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
								'app/controllers/plataforma/dashboardController.js',
								'app/services/dashboardService.js',
								'app/controllers/plataforma/contatoClientesCtrl.js',
								'app/controllers/aplicacao/headerController.js',
								'app/services/lembreteService.js'
							]
					});
				}]
			}
		})

    	.when('/agenda', {
		    templateUrl: 'views/agenda/list.html',
		    title: 'scheduling',
		    controller: 'listaAgendamentosController',
		    resolve: {
		      loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
		        return $ocLazyLoad.load({
		          name: 'app',
		          /*name module(YourModuleApp)*/
		          files: [
		          		'assets/plugins/fullcalendar/css/fullcalendar.min.css',
		          		'assets/plugins/fullcalendar/css/scheduler.min.css',
		          		// 'assets/plugins/datepicker-ADM/css/ADM-dateTimePicker.css',
		          		'app/controllers/agenda/listaAgendamentosController.js',
		          		'app/controllers/agenda/adicionarAgendamentoController.js',
		          		'app/controllers/agenda/detalharAgendamentoController.js',
		          		'app/controllers/agenda/cancelarAgendamentoController.js',
		          		'app/controllers/plataforma/contatoClientesCtrl.js',
		          		'app/controllers/aplicacao/headerController.js',
		          		'app/services/agendaService.js',
		          		'app/services/clienteService.js',
		          		'app/services/profissionalService.js',
		          		'app/services/lembreteService.js'
		          	]
		        });
		      }]
		    }
		})

		.when('/agenda/remarcacao/:id', {
		    templateUrl: 'views/agenda/reprice.html',
		    title: 'app',
		    controller: 'remarcarAgendamentoController',
		    resolve: {
		      loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
		        return $ocLazyLoad.load({
		          name: 'app',
		          /*name module(YourModuleApp)*/
		          files: [
		          		'assets/plugins/datepicker-ADM/css/ADM-dateTimePicker.css',
		          		'app/controllers/agenda/remarcarAgendamentoController.js',
		          		'app/controllers/plataforma/contatoClientesCtrl.js',
		          		'app/controllers/aplicacao/headerController.js',
		          		'app/services/agendaService.js',
		          		'app/services/servicoService.js',
		          		'app/services/lembreteService.js'
		          	]
		        });
		      }]
		    }
		})

		.when('/agenda/finalizacao/:id', {
		    templateUrl: 'views/agenda/finalize.html',
		    title: 'app',
		    controller: 'finalizarAgendamentoController',
		    resolve: {
		      loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
		        return $ocLazyLoad.load({
		          name: 'app',
		          /*name module(YourModuleApp)*/
		          files: [
		          		'app/controllers/agenda/finalizarAgendamentoController.js',
		          		'app/controllers/plataforma/contatoClientesCtrl.js',
		          		'app/controllers/aplicacao/headerController.js',
		          		'app/services/agendaService.js',
		          		'app/services/servicoService.js',
		          		'app/services/lembreteService.js'
		          	]
		        });
		      }]
		    }
		})

		.when('/agenda/comprovante/:id', {
		    templateUrl: 'views/agenda/receipt.html',
		    title: 'app',
		    controller: 'comprovanteAgendamentoController',
		    resolve: {
		      loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
		        return $ocLazyLoad.load({
		          name: 'app',
		          /*name module(YourModuleApp)*/
		          files: [
		          		'app/controllers/agenda/comprovanteAgendamentoController.js',
		          		'app/controllers/plataforma/contatoClientesCtrl.js',
		          		'app/controllers/aplicacao/headerController.js',
		          		'app/services/agendaService.js',
		          		'app/services/servicoService.js',
		          		'app/services/lembreteService.js'
		          	]
		        });
		      }]
		    }
		})

		.when('/relatorio', {
			templateUrl: 'views/relatorio/list.html',
			title: 'report',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
								'app/controllers/plataforma/contatoClientesCtrl.js',
								'app/controllers/aplicacao/headerController.js',
								'app/services/lembreteService.js'
							]
					});
				}]
			}
		})

		.when('/relatorio/agendamento/status/servicos', {
			templateUrl: 'views/relatorio/status_services.html',
			title: 'reportscheduling',
		 	controller: 'relatorioStatusServicoController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
								'app/controllers/relatorio/relatorioStatusServicoController.js',
								'app/controllers/plataforma/contatoClientesCtrl.js',
								'app/controllers/aplicacao/headerController.js',
								'app/services/relatorioService.js',
								'app/services/servicoService.js',
								'assets/plugins/datepicker-ADM/css/ADM-dateTimePicker.css',
								'app/services/lembreteService.js'
							]
					});
				}]
			}
		})

		.when('/relatorio/agendamento/status/profissional', {
			templateUrl: 'views/relatorio/status_professional.html',
			title: 'reportscheduling',
		 	controller: 'relatorioStatusProfissionalController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
								'app/controllers/relatorio/relatorioStatusProfissionalController.js',
								'app/controllers/plataforma/contatoClientesCtrl.js',
								'app/controllers/aplicacao/headerController.js',
								'app/services/relatorioService.js',
								'app/services/profissionalService.js',
								'assets/plugins/datepicker-ADM/css/ADM-dateTimePicker.css',
								'app/services/lembreteService.js'
							]
					});
				}]
			}
		})

		.when('/relatorio/agendamento/servicos/profissional', {
			templateUrl: 'views/relatorio/services_professional.html',
			title: 'reportscheduling',
		 	controller: 'relatorioServicoProfissionalController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
								'app/controllers/relatorio/relatorioServicoProfissionalController.js',
								'app/controllers/plataforma/contatoClientesCtrl.js',
								'app/controllers/aplicacao/headerController.js',
								'app/services/relatorioService.js',
								'app/services/profissionalService.js',
								'app/services/servicoService.js',
								'assets/plugins/datepicker-ADM/css/ADM-dateTimePicker.css',
								'app/services/lembreteService.js'
							]
					});
				}]
			}
		})

		.when('/relatorio/agendamento/status/servicos/profissional', {
			templateUrl: 'views/relatorio/status_services_professional.html',
			title: 'reportscheduling',
		 	controller: 'relatorioStatusServicoProfissionalController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
								'app/controllers/relatorio/relatorioStatusServicoProfissionalController.js',
								'app/controllers/plataforma/contatoClientesCtrl.js',
								'app/controllers/aplicacao/headerController.js',
								'app/services/relatorioService.js',
								'app/services/profissionalService.js',
								'app/services/servicoService.js',
								'assets/plugins/datepicker-ADM/css/ADM-dateTimePicker.css',
								'app/services/lembreteService.js'
							]
					});
				}]
			}
		})

		.when('/configuracoes/marketplace',{
			templateUrl: 'views/configuracoes/marketplace.html',
			title: 'app',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'app/controllers/plataforma/contatoClientesCtrl.js',
                    		'app/controllers/aplicacao/headerController.js',
                    		'app/services/lembreteService.js'
                    	]
                	});
				}]
			}
		})

		.when('/configuracoes/list', {
		    templateUrl: 'views/configuracoes/list.html',
		    title: 'staff',
		    resolve: {
		      loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
		        return $ocLazyLoad.load({
		          name: 'app',
		          /*name module(YourModuleApp)*/
		          files: [
		          		'app/controllers/profissionais/listarProfissionaisController.js',
		          		'app/controllers/servicos/listarServicosController.js',
		          		'app/controllers/plataforma/contatoClientesCtrl.js',
		          		'app/controllers/aplicacao/headerController.js',
		          		'app/services/agendaService.js',
		          		'app/services/clienteService.js',
		          		'app/services/profissionalService.js',
		          		'app/services/servicoService.js',
		          		'app/services/lembreteService.js'
		          	]
		        });
		      }]
		    }
		})

		.when('/configuracoes/list/:attributes', {
		    templateUrl: 'views/configuracoes/list.html',
		    title: 'configuracoes',
		    resolve: {
		      loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
		        return $ocLazyLoad.load({
		          name: 'app',
		          /*name module(YourModuleApp)*/
		          files: [
		          		'app/controllers/profissionais/listarProfissionaisController.js',
		          		'app/controllers/servicos/listarServicosController.js',
		          		'app/controllers/plataforma/contatoClientesCtrl.js',
		          		'app/controllers/aplicacao/headerController.js',
		          		'app/services/agendaService.js',
		          		'app/services/clienteService.js',
		          		'app/services/profissionalService.js',
		          		'app/services/servicoService.js',
		          		'app/services/lembreteService.js'
		          	]
		        });
		      }]
		    }
		})

		.when('/configuracoes/marketplace/website',{
			templateUrl: 'views/configuracoes/website.html',
			title: 'app',
			controller: 'websiteController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'app/controllers/configuracoes/websiteController.js',
                    		'app/controllers/plataforma/contatoClientesCtrl.js',
                    		'app/controllers/aplicacao/headerController.js',
                    		'app/services/lembreteService.js'
                    	]
                	});
				}]
			}
		})

		.when('/configuracoes/marketplace/agendamento',{
			templateUrl: 'views/configuracoes/scheduling.html',
			title: 'app',
			controller: 'marketplaceAgendamentoController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'app/controllers/configuracoes/marketplaceAgendamentoController.js',
                    		'app/services/marketplaceAgendamentoService.js',
                    		'app/controllers/plataforma/contatoClientesCtrl.js',
                    		'app/controllers/aplicacao/headerController.js',
                    		'app/services/lembreteService.js'
                    	]
                	});
				}]
			}
		})

		.when('/configuracoes/marketplace/estabelecimento',{
			templateUrl: 'views/configuracoes/company.html',
			title: 'app',
			controller: 'marketplaceEstabelecimentoController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'app/controllers/configuracoes/marketplaceEstabelecimentoController.js',
                    		'app/services/marketplaceEstabelecimentoService.js',
                    		'app/controllers/plataforma/contatoClientesCtrl.js',
                    		'app/controllers/aplicacao/headerController.js',
                    		'app/services/lembreteService.js'
                    	]
                	});
				}]
			}
		})

		.when('/configuracoes/marketplace/tags',{
			templateUrl: 'views/configuracoes/tags.html',
			title: 'app',
			controller: 'marketplaceTagsController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'app/controllers/configuracoes/marketplaceTagsController.js',
                    		'app/services/marketplaceTagsService.js',
                    		'app/controllers/plataforma/contatoClientesCtrl.js',
                    		'app/controllers/aplicacao/headerController.js',
                    		'app/services/lembreteService.js'
                    	]
                	});
				}]
			}
		})

		.when('/clientes', {
			templateUrl: 'views/clientes/list.html',
			title: 'app',
			controller: 'listarClientesController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							 'app/controllers/clientes/listarClientesController.js',
							 'app/controllers/plataforma/contatoClientesCtrl.js',
							 'app/controllers/aplicacao/headerController.js',
							 'app/services/clienteService.js',
							 'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/clientes/add', {
			templateUrl: 'views/clientes/add.html',
			title: 'app',
			controller: 'adicionarClienteController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/clientes/adicionarClienteController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/clienteService.js',
							'app/services/usuarioService.js',
							'app/services/estadocidadeService.js',
							'app/services/cepService.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/clientes/edit/:id', {
			templateUrl: 'views/clientes/edit.html',
			title: 'app',
			controller: 'editarClienteController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/clientes/editarClienteController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/clienteService.js',
							'app/services/usuarioService.js',
							'app/services/estadocidadeService.js',
							'app/services/cepService.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/profissionais/add', {
			templateUrl: 'views/profissionais/add.html',
			title: 'app',
			controller: 'adicionarProfissionalController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/profissionais/adicionarProfissionalController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/services/profissionalService.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/profissionais/edit/:id', {
			templateUrl: 'views/profissionais/edit.html',
			title: 'app',
			controller: 'editarProfissionalController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/profissionais/editarProfissionalController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/services/profissionalService.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/servicos/add', {
			templateUrl: 'views/servicos/add.html',
			title: 'app',
			controller: 'adicionarServicoController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/servicos/adicionarServicoController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/services/servicoService.js',
							'app/services/profissionalService.js',
							'app/services/categoriaService.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/servicos/edit/:id', {
			templateUrl: 'views/servicos/edit.html',
			title: 'app',
			controller: 'editarServicoController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/servicos/editarServicoController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/services/servicoService.js',
							'app/services/profissionalService.js',
							'app/services/categoriaService.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/usuarios', {
			templateUrl: 'views/usuarios/list.html',
			title: 'app',
			controller: 'listarUsuariosController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							 'app/controllers/usuarios/listarUsuariosController.js',
							 'app/controllers/plataforma/contatoClientesCtrl.js',
							 'app/services/usuarioService.js',
							 'app/controllers/aplicacao/headerController.js',
							 'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/usuarios/add', {
			templateUrl: 'views/usuarios/add.html',
			title: 'app',
			controller: 'adicionarUsuarioController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/usuarios/adicionarUsuarioController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/services/usuarioService.js',
							'app/services/profissionalService.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/usuarios/edit/:id', {
			templateUrl: 'views/usuarios/edit.html',
			title: 'app',
			controller: 'editarUsuarioController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/usuarios/editarUsuarioController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/services/usuarioService.js',
							'app/services/profissionalService.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/usuarios/password-change/:id', {
			templateUrl: 'views/usuarios/password-change.html',
			title: 'app',
			controller: 'editarUsuarioController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'app/controllers/usuarios/editarUsuarioController.js',
							'app/controllers/plataforma/contatoClientesCtrl.js',
							'app/services/usuarioService.js',
							'app/services/profissionalService.js',
							'app/controllers/aplicacao/headerController.js',
							'app/services/lembreteService.js'
						]
					});
				}]
			}
		})

		.when('/404', { templateUrl: '404.html',  title: 'error application' })
		.otherwise({ redirectTo: '/404' });

});

app.config(['$resourceProvider', function($resourceProvider) {
  // Don't strip trailing slashes from calculated URLs
  $resourceProvider.defaults.stripTrailingSlashes = false;
}]);

/* directive template */
app.directive('mdAside', function(){
	return {
		restrict: 'E',
		scope: true,
		templateUrl: 'views/template/aside.html'
	};
});

app.directive('mdSidebar', function(){
	return {
		restrict: 'E',
		scope: true,
		templateUrl: 'views/template/sidebar.html'
	};
});

app.directive('mdHeader', function(){
	return {
		restrict: 'E',
		scope: true,
		templateUrl: 'views/template/header.html'
	};
});

app.directive('mdTooltipCalendar', function(){
	return {
		restrict: 'E',
		scope: true,
		templateUrl: 'views/agenda/tooltip.html'
	};
});

//run application
app.run(function($rootScope,$location,usuarioFactory,aplicacaoService){
	
	$rootScope.roles = [];
	$rootScope.users = [];

	$rootScope.$on('$routeChangeStart', function (event, next, current){

		aplicacaoService.checkLogin();

		$rootScope.$on('aplicacao:checklogin', function(event, aplicacao) {
			if(aplicacao) usuarioFactory.set(aplicacao);
		});

		$rootScope.$on('handleBroadcast', function(){
			$rootScope.users = usuarioFactory.get();

			var roles = 0;
			var url = undefined;

			if($rootScope.users.error)
				$location.path('/entrar');

			if($rootScope.users.id && $rootScope.users.roles.length){
				for(var i=0;i<$rootScope.users.roles.length;i++){
					$rootScope.roles.push($rootScope.users.roles[i].item);
				}
				for(var i=0;i<$rootScope.users.roles.length;i++){
					url = $location.path();

					if(url.indexOf($rootScope.roles[i])>=0)
						roles++;
				}
				if(!roles)
					$location.path('/entrar');
			}

		});

	});
});


//run application
// app.run(function($rootScope, $location, loginService){
// 	$rootScope.rolespermission = [];

// 	$rootScope.$on('$routeChangeStart', function (event, next, current){

// 		var $roles = 0;
// 		var $current_url = '';

// 		var $promise = loginService.roles();
// 		$promise.then(function(item){
// 			for( var i = 0; i < item.data.length; i++ ) {
// 				$rootScope.rolespermission.push(item.data[i].roles);
// 			}
// 			for( var $key = 0; $key < $rootScope.rolespermission.length; $key++ ){
// 				$current_url = $location.path();
// 				if( $current_url.indexOf( $rootScope.rolespermission[$key] ) >= 0  ){
// 					$roles++;
// 				}
// 			}
// 			if( $roles >= 1 ){
// 				var $promiseCheckLogged = loginService.islogged();
// 				$promiseCheckLogged.then(function(item){
// 					if( item.data.status == 'error' ){
// 						$location.path('/login');
// 					}
// 				});
// 			}else if( $roles <= 0 ){
// 				$location.path('/login');
// 			}
// 		});

// 	});
// });
