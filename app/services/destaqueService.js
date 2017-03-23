angular.module('app').service('destaqueService', ['$rootScope', '$timeout', '$http' , function ($rootScope, $timeout, $http) {
  var self = this

  this.set = function (destaque) {
    self.destaque = destaque
    $rootScope.$broadcast('destaque', destaque)
  }

  this.setIds = function (ids) {
    self.ids = ids
  }

  this.getList = function () {
    $rootScope.$broadcast('destaques:loading', true)
    $http.post('/controller/marketplace/destaque/list', self.destaque)
      .success(function (destaques) {
        $rootScope.$broadcast('destaques:loading', false)
        $rootScope.$broadcast('destaques:list', destaques)
      })
      .error(function (destaques) {
        $rootScope.$broadcast('destaques:loading', false)
        $rootScope.$broadcast('destaques:list', destaques)
      })
  }

  this.getSegments = function () {
    $rootScope.$broadcast('destaques:segments:loading', true)
    $http.post('/controller/marketplace/destaque/segments', self.destaque)
      .success(function (segments) {
        $rootScope.$broadcast('destaques:segments:loading', false)
        $rootScope.$broadcast('destaques:segments', segments)
      })
      .error(function (segments) {
        $rootScope.$broadcast('destaques:segments:loading', false)
        $rootScope.$broadcast('destaques:segments', segments)
      })
  }

  this.load = function () {
    $rootScope.$broadcast('destaque:loading', true)
    $http.post('/controller/marketplace/destaque/get', self.destaque)
      .success(function (destaque) {
        $rootScope.$broadcast('destaque:loading', false)
        $rootScope.$broadcast('destaque', destaque)
      })
      .error(function (destaque) {
        $rootScope.$broadcast('destaque:loading', false)
        $rootScope.$broadcast('destaque', destaque)
      })
  }


}])
