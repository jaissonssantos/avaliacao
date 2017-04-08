angular.module('app').controller('adicionarPublicidadeController', ['$location', '$rootScope', '$scope', 'publicidadeService', 'estabelecimentoService', function ($location, $rootScope, $scope, publicidadeService, estabelecimentoService) {
  $scope.publicidade = {
    idestabelecimento: null,
    tipo_publicidade: null,
    data_inicio: null,
    data_fim: null,
    valor: 0
  }
  $scope.upload = {}

  $rootScope.$on('publicidade:save', function (event, status) {
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
    $scope.publicidade.idestabelecimento = $scope.estabelecimentos[0].id
  })

  $scope.save = function () {
    publicidadeService.set($scope.publicidade)
    publicidadeService.save()
  }

  $scope.loadEstabelecimentos =  function () {
    estabelecimentoService.getList()
  }
}])
