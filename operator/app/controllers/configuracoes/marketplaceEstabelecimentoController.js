'use strict'; 

app
	.controller('marketplaceEstabelecimentoController', ['$scope', '$rootScope', '$http', '$timeout', 'marketplaceEstabelecimentoService', '$location', function($scope, $rootScope, $http, $timeout, marketplaceEstabelecimentoService, $location){

		/*variables*/
		$scope.estabelecimento 		= [];
		$scope.deleteimage 			= [];

		$rootScope.$on('estabelecimento', function(event, estabelecimento){
			$scope.estabelecimento = estabelecimento;
		});

		$rootScope.$on('estabelecimento:update', function(event, status){
			$scope.status = {
				loading: (status == 'loading'),
				success: (status == 'success'),
				error: (status == 'error')
			}
			if($scope.status.success){
				$location.path('configuracoes/marketplace/estabelecimento');
			}
		});

		$rootScope.$on('estabelecimentos:message:success', function(event, message){
			$rootScope.success = message;
		});

		$rootScope.$on('estabelecimentos:message:error', function(event, message){
			$rootScope.error = message;
		});

		$scope.load = function(){
			marketplaceEstabelecimentoService.load();
		}

		$scope.update = function(){
			marketplaceEstabelecimentoService.set($scope.estabelecimento);
			marketplaceEstabelecimentoService.update();
		}

		$scope.delImagem = function(index, item){
			$scope.estabelecimento.imagem.splice(index, 1);
			$scope.deleteimage.push({id: item.id, file: item.file});
			$scope.estabelecimento.imagemdeletar = $scope.deleteimage;
			console.log($scope.estabelecimento);
		}

	}]);