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
		    'ngTagsInput'
		  ]);


/* configuration and routs */
app.config( function($routeProvider, $locationProvider){

	// use the HTML5 History API
    $locationProvider.html5Mode(true);

	$routeProvider
		.when('/gestor',{
			templateUrl: '/gestor/views/login.html',
			title: 'entrar',
			controller: 'entrarController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'/gestor/app/controllers/aplicacao/entrarController.js'
                    	]
                	});
				}]
			}
		})

		.when('/gestor/entrar',{
			templateUrl: '/gestor/views/login.html',
			title: 'entrar',
			controller: 'entrarController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load({
                    	name: 'app',
                    	files: [
                    		'/gestor/app/controllers/aplicacao/entrarController.js'
                    	]
                	});
				}]
			}
		})

		.when('/gestor/dashboard', {
		    templateUrl: '/gestor/views/dashboard.html',
		    title: 'dashboard',
		    resolve: {
		      loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
		        return $ocLazyLoad.load({
		          name: 'app',
		          /*name module(YourModuleApp)*/
		          files: [
		          		'/gestor/app/services/dashboardService.js',
		          		'/gestor/app/controllers/aplicacao/headerController.js'
		          	]
		        });
		      }]
		    }
		})

		.when('/gestor/categorias', {
			templateUrl: '/gestor/views/categoria/list.html',
			title: 'app',
			controller: 'listCategoriaController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/categoria/listCategoriaController.js',
							'/gestor/app/services/categoriaService.js',
		          			'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/categorias/add', {
			templateUrl: '/gestor/views/categoria/add.html',
			title: 'app',
			controller: 'addCategoriaController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/categoria/addCategoriaController.js',
							'/gestor/app/services/categoriaService.js',
		          			'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/categorias/edit/:id', {
			templateUrl: '/gestor/views/categoria/add.html',
			title: 'app',
			controller: 'addCategoriaController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/categoria/addCategoriaController.js',
							'/gestor/app/services/categoriaService.js',
		          			'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/servicos', {
			templateUrl: '/gestor/views/servicos/list.html',
			title: 'app',
			controller: 'listarServicosController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/servicos/listarServicosController.js',
							'/gestor/app/controllers/plataforma/contatoClientesCtrl.js',
							'/gestor/app/services/servicoService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/servicos/add', {
			templateUrl: '/gestor/views/servicos/add.html',
			title: 'app',
			controller: 'adicionarServicoController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/servicos/adicionarServicoController.js',
							'/gestor/app/controllers/plataforma/contatoClientesCtrl.js',
							'/gestor/app/services/servicoService.js',
							'/gestor/app/services/profissionalService.js',
							'/gestor/app/services/categoriaService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/servicos/edit/:id', {
			templateUrl: '/gestor/views/servicos/edit.html',
			title: 'app',
			controller: 'editarServicoController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/servicos/editarServicoController.js',
							'/gestor/app/controllers/plataforma/contatoClientesCtrl.js',
							'/gestor/app/services/servicoService.js',
							'/gestor/app/services/profissionalService.js',
							'/gestor/app/services/categoriaService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/usuarios', {
			templateUrl: '/gestor/views/usuarios/list.html',
			title: 'app',
			controller: 'listarUsuariosController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							 '/gestor/app/controllers/usuarios/listarUsuariosController.js',
							 '/gestor/app/controllers/plataforma/contatoClientesCtrl.js',
							 '/gestor/app/services/usuarioService.js',
							 '/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/usuarios/add', {
			templateUrl: '/gestor/views/usuarios/add.html',
			title: 'app',
			controller: 'adicionarUsuarioController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/usuarios/adicionarUsuarioController.js',
							'/gestor/app/controllers/plataforma/contatoClientesCtrl.js',
							'/gestor/app/services/usuarioService.js',
							'/gestor/app/services/profissionalService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/usuarios/edit/:id', {
			templateUrl: '/gestor/views/usuarios/edit.html',
			title: 'app',
			controller: 'editarUsuarioController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/usuarios/editarUsuarioController.js',
							'/gestor/app/controllers/plataforma/contatoClientesCtrl.js',
							'/gestor/app/services/usuarioService.js',
							'/gestor/app/services/profissionalService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/usuarios/password-change/:id', {
			templateUrl: '/gestor/views/usuarios/password-change.html',
			title: 'app',
			controller: 'editarUsuarioController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/usuarios/editarUsuarioController.js',
							'/gestor/app/controllers/plataforma/contatoClientesCtrl.js',
							'/gestor/app/services/usuarioService.js',
							'/gestor/app/services/profissionalService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/publicidades', {
			templateUrl: '/gestor/views/publicidades/list.html',
			title: 'app',
			controller: 'listarPublicidadesController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							 '/gestor/app/controllers/publicidades/listarPublicidadesController.js',
							 '/gestor/app/services/publicidadeService.js',
							 '/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/publicidades/add', {
			templateUrl: '/gestor/views/publicidades/add.html',
			title: 'app',
			controller: 'adicionarPublicidadeController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/publicidades/adicionarPublicidadeController.js',
							'/gestor/app/services/publicidadeService.js',
							'/gestor/app/services/estabelecimentoService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/publicidades/edit/:id', {
			templateUrl: '/gestor/views/publicidades/edit.html',
			title: 'app',
			controller: 'editarPublicidadeController',
			resolve: {
				loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
					return $ocLazyLoad.load({
						name: 'app',
						files: [
							'/gestor/app/controllers/publicidades/editarPublicidadeController.js',
							'/gestor/app/services/publicidadeService.js',
							'/gestor/app/services/estabelecimentoService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
						]
					});
				}]
			}
		})

		.when('/gestor/estabelecimentos', {
	        templateUrl: '/gestor/views/estabelecimentos/list.html',
	        title: 'app',
	        controller: 'listarEstabelecimentosController',
	        resolve: {
	            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
	                return $ocLazyLoad.load({
	                    name: 'app',
	                    files: [
	                        '/gestor/app/controllers/estabelecimento/listarEstabelecimentosController.js',
	                        '/gestor/app/services/estabelecimentoService.js',
	                        '/gestor/app/controllers/aplicacao/headerController.js'
	                    ]
	                });
	            }]
	        }
	    })

	    .when('/gestor/estabelecimentos/add', {
	        templateUrl: '/gestor/views/estabelecimentos/add.html',
	        title: 'app',
	        controller: 'adicionarEstabelecimentoController',
	        resolve: {
	            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
	                return $ocLazyLoad.load({
	                    name: 'app',
	                    files: [
	                        '/gestor/app/controllers/estabelecimento/adicionarEstabelecimentoController.js',
	                        '/gestor/app/services/estabelecimentoService.js',
							'/gestor/app/services/estadocidadeService.js',
							'/gestor/app/services/cepService.js',
							'/gestor/app/services/formaPagamentoService.js',
							'/gestor/app/services/segmentoService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
	                    ]
	                });
	            }]
	        }
	    })

	    .when('/gestor/estabelecimentos/edit/:id', {
	        templateUrl: '/gestor/views/estabelecimentos/edit.html',
	        title: 'app',
	        controller: 'editarEstabelecimentoController',
	        resolve: {
	            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
	                return $ocLazyLoad.load({
	                    name: 'app',
	                    files: [
	                        '/gestor/app/controllers/estabelecimento/editarEstabelecimentoController.js',
	                        '/gestor/app/services/estabelecimentoService.js',
							'/gestor/app/services/segmentoService.js',
							'/gestor/app/services/estadocidadeService.js',
							'/gestor/app/services/cepService.js',
							'/gestor/app/services/formaPagamentoService.js',
							'/gestor/app/controllers/aplicacao/headerController.js'
	                    ]
	                });
	            }]
	        }
	    })

		.when('/gestor/404', { templateUrl: '404.html',  title: 'error application' })
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
		templateUrl: '/gestor/views/template/aside.html'
	};
});

app.directive('mdSidebar', function(){
	return {
		restrict: 'E',
		scope: true,
		templateUrl: '/gestor/views/template/sidebar.html'
	};
});

app.directive('mdHeader', function(){
	return {
		restrict: 'E',
		scope: true,
		templateUrl: '/gestor/views/template/header.html'
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
				$location.path('/gestor/entrar');

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
					$location.path('/gestor/entrar');
			}

		});

	});
});
