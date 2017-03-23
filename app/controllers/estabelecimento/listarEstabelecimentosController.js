'use strict'; 

angular.module('app').controller('listarEstabelecimentosController', ['$rootScope','$scope','$http','$routeParams','$timeout','$location','$uibModal','uiGmapGoogleMapApi','ngProgressFactory','estabelecimentoService','clienteFactory','aplicacaoService',
	function($rootScope,$scope,$http,$routeParams,$timeout,$location,$uibModal,uiGmapGoogleMapApi,ngProgressFactory,estabelecimentoService,clienteFactory,aplicacaoService){
		
		$scope.ngProgressApp = ngProgressFactory.createInstance();
		$scope.ngProgressApp.start();
		$scope.estabelecimento = {
			hash: $routeParams.hash || undefined
		};
		$scope.aplicacao = {};
		$scope.results = {};
		$scope.dataAtual = undefined;
		$scope.domingo = false;
		$scope.map = {};
		$scope.markers = [];
		$scope.clients = {};
		$scope.avaliame = false;

		$scope.$on('handleBroadcast', function(){
            $scope.clients = clienteFactory.get();
        });

		$rootScope.$on('estabelecimento', function(event, estabelecimento){
			$scope.estabelecimento = estabelecimento.results;
			if($routeParams.hash==undefined)
				$location.path('/404');
			if(estabelecimento.results!=undefined){
				if($scope.estabelecimento.pagina!=undefined)
					$location.path('/pagina/'+$scope.estabelecimento.hash);
				if($scope.estabelecimento.atendimento){
					for(var i=0;i<$scope.estabelecimento.atendimento.length;i++){
						if(parseInt($scope.estabelecimento.atendimento[i].dia)==0)
							$scope.domingo=true;
					}
				}
				if($scope.estabelecimento.localizacao!=undefined){
					var location = $scope.estabelecimento.localizacao.split(',');
					$scope.map = { 
						center: { 
							latitude: location[0],
							longitude: location[1] 
						}, 
						zoom: 15 
					};
					$scope.markers.push({
						'id': $scope.estabelecimento.id,
						'estabelecimento': $scope.estabelecimento.nomefantasia,
						'coords': {
							'latitude': location[0],
							'longitude': location[1]
						}
					});

				}
				$scope.ngProgressApp.complete();
			}
		});

		$rootScope.$on("estabelecimento:loading", function(event, status){
			$scope.results.loading = status;
		});

		$rootScope.$on('aplicacao', function(event, aplicacao){
			$scope.aplicacao = aplicacao.results;
			if($scope.aplicacao.ano)
				$scope.dataAtual = new Date($scope.aplicacao.ano,$scope.aplicacao.mes-1,$scope.aplicacao.dia);
		});

		$scope.load = function(){
			estabelecimentoService.set($scope.estabelecimento);
			estabelecimentoService.load();

			aplicacaoService.getDate();
		}

		//avaliação
		$scope.rating = function(){
			if(!$scope.clients.id){
				$scope.avaliame = true;
				$timeout(function(){
		         $scope.avaliame = false;
		        }, 8000);
			}else{
				var modalInstance = $uibModal.open({
					templateUrl: 'views/estabelecimento-avaliacao.html',
					controller: 'avaliacaoEstabelecimentosController',
					size: 'modal-sm',
					resolve: {
						estabelecimento: function(){
							return $scope.estabelecimento;
						}
					}
				});
			}
		}

		$scope.ratingCancel = function(){
			$scope.avaliame = false;
		}

		$scope.signup = function(){
			$location.url('/cadastro');
		}

		$scope.login = function(){
			var url = $location.url();
			$location.url('/entrar?redirect='+url);
		}

		//google maps
		uiGmapGoogleMapApi.then(function(maps){});

	}])