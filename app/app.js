'use strict';

window.app = angular.module('app', [
  'ng',
  'ngResource',
  'ngRoute',
  'ngAnimate',
  'ui.bootstrap',
  'oc.lazyLoad',
  'ngProgress',
  'ui.utils.masks',
  'ui.mask',
  'idf.br-filters',
  'uiGmapgoogle-maps',
  'angular-owl-carousel',
  'rzModule',
  '720kb.tooltips',
  'ngFileUpload',
  'credit-cards'
]);

/* configuration and routs */
angular.module('app').config(['$routeProvider','$locationProvider','uiGmapGoogleMapApiProvider',  function($routeProvider,$locationProvider,uiGmapGoogleMapApiProvider) {

  uiGmapGoogleMapApiProvider.configure({
      //key: 'your api key',
      v: '3.17', //defaults to latest 3.X anyhow
      libraries: 'weather,geometry,visualization'
  });

  //remove the # in URLs
  $locationProvider.html5Mode(true);

  $routeProvider
    .when('/cliente', {
      templateUrl: 'views/cliente.html',
      title: 'cliente',
      controller: 'perfilController',
      resolve: {
        lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
          return $ocLazyLoad.load({
            name: 'app',
            /*name module(YourModuleApp)*/
            files: [
              'app/services/clienteService.js', 
              'app/services/agendamentoService.js', 
              'app/controllers/cliente/perfilController.js',
              'app/controllers/aplicacao/buscaHomeController.js',
              'app/controllers/aplicacao/headerController.js',
              'assets/css/principal.css'
            ]
          });
        }]
      }
    })

  .when('/cliente/:tab', {
    templateUrl: 'views/cliente.html',
    title: 'cliente',
    controller: 'perfilController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js', 
            'app/services/agendamentoService.js',
            'app/controllers/cliente/perfilController.js',
            'app/controllers/aplicacao/buscaHomeController.js',
            'app/controllers/aplicacao/headerController.js',
            'assets/css/principal.css'
          ]
        });
      }]
    }
  })

  .when('/pagina/:hash', {
    templateUrl: 'views/pagina.html',
    title: 'pagina',
    controller: 'paginaEstabelecimentosController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js', 
            'app/services/estabelecimentoService.js',
            'app/controllers/estabelecimento/paginaEstabelecimentosController.js',
            'assets/landingpagefirst/css/animate.css',
            'assets/landingpagefirst/css/bootstrap.min.css',
            'assets/landingpagefirst/css/style.css',
            'assets/landingpagefirst/css/default.css',
            'assets/landingpagefirst/js/jquery.easing.min.js',
            'assets/landingpagefirst/js/jquery.scrollTo.js',
            'assets/landingpagefirst/js/wow.min.js',
            'assets/landingpagefirst/js/custom.js'
          ]
        });
      }]
    }
  })

  .when('/busca/', {
    templateUrl: 'views/busca.html',
    title: 'busca',
    controller: 'buscaController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          files: [
            'app/services/clienteService.js',
            'app/controllers/aplicacao/buscaController.js', 
            'app/controllers/aplicacao/buscaHomeController.js',
            'app/controllers/aplicacao/headerController.js',
            'assets/css/principal.css'
          ]
        });
      }]
    }
  })


.when('/cadastro-estabelecimento/', {
    templateUrl: 'views/cadastro-estabelecimento.html',
    title: 'app',
    controller: 'adicionarEstabelecimentoController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js', 
           'app/controllers/estabelecimento/adicionarEstabelecimentoController.js',
            'app/services/estabelecimentoService.js',
						'app/services/estadocidadeService.js',
						'app/services/cepService.js',
						'app/services/formaPagamentoService.js',
						'app/services/segmentoService.js',
            'assets/css/principal.css',
            'assets/css/estabelecimento.css'
          ]
        });
      }]
    }
  })

  .when('/estabelecimento/:hash', {
    templateUrl: 'views/estabelecimento.html',
    title: 'estabelecimento',
    controller: 'listarEstabelecimentosController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js', 
            'app/services/estabelecimentoService.js', 
            'app/services/avaliacaoService.js', 
            'app/controllers/aplicacao/headerController.js',
            'app/controllers/aplicacao/buscaHomeController.js',
            'app/controllers/estabelecimento/listarEstabelecimentosController.js',
            'app/controllers/estabelecimento/avaliacaoEstabelecimentosController.js',
            'assets/css/principal.css',
            'assets/css/rzslider.css'
          ]
        });
      }]
    }
  })

  .when('/estabelecimento/:hash/agendamento/:service', {
    templateUrl: 'views/agendamento.html',
    title: 'agendamento',
    controller: 'agendarController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js', 
            'app/services/agendamentoService.js', 
            'app/services/estabelecimentoService.js', 
            'app/controllers/aplicacao/headerController.js',
            'app/controllers/aplicacao/buscaHomeController.js',
            'app/controllers/agendamento/agendarController.js', 
            'assets/css/principal.css'
          ]
        });
      }]
    }
  })

  .when('/estabelecimento/:hash/agendamento/:service/reagendar/:agendamento', {
    templateUrl: 'views/agendamento.html',
    title: 'reagendar',
    controller: 'agendarController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js',
            'app/services/agendamentoService.js', 
            'app/services/estabelecimentoService.js',
            'app/controllers/aplicacao/buscaHomeController.js',
            'app/controllers/agendamento/agendarController.js', 
            'assets/css/principal.css'
          ]
        });
      }]
    }
  })

  .when('/segmentos/:segment', {
    templateUrl: 'views/segmentos.html',
    title: 'Segmentos',
    controller: 'listarDestaquesController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          files: [
            'app/controllers/aplicacao/headerController.js',
            'app/services/clienteService.js',
            'assets/css/principal.css',
            'app/controllers/aplicacao/listarDestaquesController.js', 
            'app/services/destaqueService.js', 
            'app/controllers/aplicacao/buscaHomeController.js'
          ]
        });
      }]
    }
  })

  .when('/entrar', {
    templateUrl: 'views/entrar.html',
    title: 'entrar',
    controller: 'entrarController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          files: [
            'app/services/clienteService.js',
            'app/controllers/aplicacao/entrarController.js'
          ]
        });
      }]
    }
  })

  .when('/cadastro', {
    templateUrl: 'views/cadastro.html',
    title: 'cadastro',
    controller: 'cadastroController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js',
            'app/services/sessionService.js',
            'app/services/authenticateMarketplaceSrv.js',
            'app/controllers/aplicacao/cadastroController.js'
            ]
        });
      }]
    }
  })

  .when('/redefinir-senha', {
    templateUrl: 'views/redefinir-senha.html',
    title: 'redefinir senha',
    controller: 'redefinirSenhaController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          files: [
            'app/services/clienteService.js',
            'app/controllers/aplicacao/redefinirSenhaController.js'
          ]
        });
      }]
    }
  })

  .when('/alterar-senha/:token', {
    templateUrl: 'views/alterar-senha.html',
    title: 'alterar senha',
    controller: 'alterarSenhaController',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          files: [
            'app/services/clienteService.js',
            'app/controllers/aplicacao/alterarSenhaController.js'
          ]
        });
      }]
    }
  })

  .when('/termos-de-uso', {
    templateUrl: 'views/termos-de-uso.html',
    title: 'termos de uso',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js',
            'assets/css/principal.css', 
            'app/controllers/aplicacao/buscaHomeController.js'
          ]
        });
      }]
    }
  })

  .when('/politica-de-privacidade', {
    templateUrl: 'views/politica-de-privacidade.html',
    title: 'politica de privacidade',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js',
            'assets/css/principal.css', 
            'app/controllers/aplicacao/buscaHomeController.js'
          ]
        });
      }]
    }
  })

  .when('/contato', {
    templateUrl: 'views/contato.html',
    title: 'contato',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js',
            'assets/css/principal.css', 
            'app/controllers/aplicacao/buscaHomeController.js'
          ]
        });
      }]
    }
  })

  .when('/sobre', {
    templateUrl: 'views/sobre.html',
    title: 'sobre',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js', 
            'assets/css/principal.css', 
            'app/controllers/aplicacao/buscaHomeController.js'
          ]
        });
      }]
    }
  })

  .when('/faq', {
    templateUrl: 'views/faq.html',
    title: 'faq',
    // controller: 'passwordchangeUserCtrl',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          /*name module(YourModuleApp)*/
          files: [
            'app/services/clienteService.js',
            'assets/css/principal.css', 
            'app/controllers/aplicacao/buscaHomeController.js'
          ]
        });
      }]
    }
  })

  .when('/', {
    templateUrl: 'views/home.html',
    title: 'home',
    resolve: {
      lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
        return $ocLazyLoad.load({
          name: 'app',
          files: [
            
            'assets/css/principal.css', 
            'app/services/destaqueService.js',
            'app/controllers/aplicacao/buscaHomeController.js',
            'app/controllers/aplicacao/destaquesHomeController.js',
            'app/controllers/aplicacao/headerController.js',
            'app/services/clienteService.js'
          ]
        });
      }]
    }
  })

  .when('/404', {
      templateUrl: '404.html',
      title: 'error application'
  })

  .otherwise({
      redirectTo: '/404'
  });

}]);

/* directive template */
app.directive('mdSide', function() {
  return {
    restrict: 'E',
    scope: true,
    templateUrl: 'template/aside.html'
  };
});

app.directive('mdSidebar', function() {
  return {
    restrict: 'E',
    scope: true,
    templateUrl: 'template/sidebar.html'
  };
});

app.directive('templateHeaderHome', function() {
  return {
    restrict: 'E',
    scope: true,
    templateUrl: 'views/template/marketplace/header-home.html',
    controller: 'headerController'
  };
});

app.directive('templateHeader', function() {
  return {
    restrict: 'E',
    scope: true,
    templateUrl: 'views/template/marketplace/header.html',
    controller: 'buscaHomeController'
  };
});

app.directive('templateFooter', function() {
  return {
    restrict: 'E',
    scope: true,
    templateUrl: 'views/template/marketplace/footer.html'
  };
});

app.directive('templateHeaderPageFirst', function() {
  return {
    restrict: 'E',
    scope: true,
    templateUrl: 'views/template/landingpagefirst/header.html'
  };
});

app.directive('templateFooterPageFirst', function() {
  return {
    restrict: 'E',
    scope: true,
    templateUrl: 'views/template/landingpagefirst/footer.html'
  };
});

