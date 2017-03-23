'use strict'

angular.module('app').controller('buscaController', ['$scope', '$http', '$routeParams', '$location', 'ngProgressFactory', function ($scope, $http, $routeParams, $location, ngProgressFactory) {

    /*loading page start*/
    $scope.ngProgressApp = ngProgressFactory.createInstance()
    $scope.ngProgressApp.start()

    /*variables*/
    $scope.companies = []
    $scope.loadersearch = false
    $scope.companiesTotal = undefined
    var paramQueryGeo = $location.search().geo
    var paramQueryServices = $location.search().services

    /*params for paginator*/
    $scope.totalItems = 0
    $scope.currentPage = 1 // current page
    $scope.numPerPage = 10 // number item show container
    $scope.entryLimit = 5 // max no of items to display in a page in pagination

    /*functions paginate companies*/
    $scope.paginate = function (value) {
      var begin, end, index
      begin = ($scope.currentPage - 1) * $scope.numPerPage
      end = begin + $scope.numPerPage
      index = $scope.companies.indexOf(value) // index in companies
      return (begin <= index && index < end)
    }

    $scope.changePaginate = function () {
      $scope.getCompanys()
    }
    $scope.loadersearch = true
    $scope.getCompanys = function () {
      /*verificated params for search*/
      if (paramQueryServices != undefined || paramQueryGeo != undefined) {
        paramQueryGeo = (paramQueryGeo == '' ? 'null' : paramQueryGeo)
        paramQueryServices = (paramQueryServices == '' ? 'null' : paramQueryServices)
        $scope.loadersearch = true
        var $limitmin = ($scope.currentPage - 1) * $scope.numPerPage
        var $limitmax = $limitmin + $scope.numPerPage
        $scope.query = paramQueryServices;
        var $promise = $http.post('/controller/marketplace/getcompaniessearch', {services: paramQueryServices, geo: paramQueryGeo, limitmin: $limitmin, limitmax: $limitmax})
        $promise.then(function (response) {
          $scope.companies = response.data.results
		      $scope.filter_service = response.data.filter_service
          $scope.loadersearch = false
          $scope.ngProgressApp.complete()
        })
      }else {
        console.log('nenhum registro encontrado!')
      }
    }
  }])
