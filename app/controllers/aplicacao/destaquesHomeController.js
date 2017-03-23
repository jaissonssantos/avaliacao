'use strict'

angular.module('app').controller('destaquesHomeController', ['$rootScope','$scope','destaqueService', 
  function ($rootScope,$scope,destaqueService) {
  destaqueService.set({
    limit: 3
  })

  $rootScope.$on('destaques:segments', function (event, segments) {
    $rootScope.segments = segments.results
  })

  $rootScope.$on('destaques:list', function (event, destaques) {
    $rootScope.destaques = destaques.results
  })

  $scope.getSegments = function () {
    destaqueService.getSegments()
  }

  $scope.getDestaques = function () {
    destaqueService.getList()
  }
}])
