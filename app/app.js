'use strict';

window.app = angular.module('app', [
  'ng',
  'ngResource',
  'ngRoute',
  'ui.bootstrap',
  'oc.lazyLoad',
  'ui.utils.masks'
]);

/* configuration and routs */
angular.module('app').config(['$routeProvider','$locationProvider',  function($routeProvider,$locationProvider) {
  
  //remove the # in URLs
  $locationProvider.html5Mode(true);

  $routeProvider
    .when('/', {
      templateUrl: 'views/home.html',
      title: 'home',
      resolve: {
        lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
          return $ocLazyLoad.load({
            name: 'app',
            files: [
            ]
          });
        }]
      }
    })

    .when('/cadastro/estabelecimento', {
      templateUrl: 'views/estabelecimento.html',
      title: 'cadastro do estabelecimento',
      controller: 'adicionarEstabelecimentoController',
      resolve: {
        lazyTestCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
          return $ocLazyLoad.load({
            name: 'app',
            /*name module(YourModuleApp)*/
            files: [
              'app/services/estabelecimentoService.js', 
              'app/services/estadocidadeService.js', 
              'app/services/cepService.js', 
              'app/services/usuarioService.js',
              'app/controllers/estabelecimento/adicionarEstabelecimentoController.js',
              'assets/css/estabelecimento.css', 
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

  .when('/404', {
      templateUrl: '404.html',
      title: 'error application'
  })

  .otherwise({
      redirectTo: '/404'
  });

}]);