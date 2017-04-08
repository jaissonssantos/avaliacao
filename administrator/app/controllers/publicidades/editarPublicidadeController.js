angular.module('app').controller('editarPublicidadeController', ['$location', '$rootScope', '$scope', '$routeParams', 'publicidadeService', 'estabelecimentoService', function ($location, $rootScope, $scope, $routeParams, publicidadeService, estabelecimentoService) {
  $scope.publicidade = {
    id: $routeParams.id
  }
  $scope.upload = {}

  $rootScope.$on('publicidade', function (event, publicidade) {
    $scope.publicidade = publicidade
  })

  $rootScope.$on('publicidade:update', function (event, status) {
    $scope.status = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    }

    if ($scope.status.success) {
      $location.path('/gestor/publicidades')
    }
  })

  $rootScope.$on('estabelecimentos', function (event, estabelecimentos) {
    $scope.estabelecimentos = estabelecimentos
  })

  $scope.loadEstabelecimentos = function () {
    estabelecimentoService.getList()
  }

  $scope.load = function () {
    publicidadeService.set($scope.publicidade)
    publicidadeService.load()
  }

  $scope.update = function () {
    publicidadeService.set($scope.publicidade)
    publicidadeService.update()
  }
}])
