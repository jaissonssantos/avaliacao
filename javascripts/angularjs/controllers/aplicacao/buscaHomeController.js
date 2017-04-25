'use strict'; 

angular.module('app').controller('buscaHomeController', ['$rootScope','$scope','$http','$location','clienteFactory','aplicacaoService','zipCodeFactory',
    function($rootScope,$scope,$http,$location,clienteFactory,aplicacaoService,zipCodeFactory){
		
		$scope.busca = {};
        $scope.clients = {};
        $scope.types = "['geocode']";

        $scope.$on('handleBroadcast', function(){
            $scope.clients = clienteFactory.get();
        });  

		$scope.search = function(){
			if($scope.busca.servico != undefined){
            	$location.url('/busca?services='+$scope.busca.servico);
			}
        }

        $scope.searchKey = function(event){
        	if (event.keyCode==13) {
        		$scope.search();
        	}
        }

		$scope.geoLocation = function(){
        	zipCodeFactory.lookup().then(function(cep) {
		        $scope.busca.localizacao = cep;
		        // $scope.search();
		      }, function(error){
		      	alert('ocorreu um erro ao tentar pega sua localização atual!');
		    });
        }

	}])