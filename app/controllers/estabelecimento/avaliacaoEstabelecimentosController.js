'use strict'; 

angular.module('app').controller('avaliacaoEstabelecimentosController', ['$rootScope','$scope','$uibModalInstance','$location','estabelecimento','estabelecimentoService','avaliacaoService',
function($rootScope,$scope,$uibModalInstance,$location,estabelecimento,estabelecimentoService,avaliacaoService) {

  	$scope.rating_max = 5;
  	$scope.ratingStates = [
	    {stateOn: 'fa fa-star', stateOff: 'fa fa-star-o'}
	];
	$scope.avaliacao = {
		parametro:{
			idestabelecimento: estabelecimento.id
		}
	};
	$scope.results = {};

	$rootScope.$on('avaliacoes:message:success', function(event, message) {
		$scope.success = message;
	});

	$rootScope.$on('avaliacoes:message:error', function(event, message) {
		$scope.error = message;
	});

	$rootScope.$on('avaliacao:rating', function(event, status) {
	    $scope.status = {
	      loading: (status == 'loading'),
	      success: (status == 'success'),
	      error: (status == 'error')
	    };
  	});

	$scope.rating = function(){
		avaliacaoService.set($scope.avaliacao);
		avaliacaoService.estabelecimentoRating();
	}

	$scope.cancel = function(){
		$uibModalInstance.dismiss('cancel');
	}

	$scope.countComent = function(count){
		var max = 500;
		count = count!=undefined || count > 0 ? count : 0;  
		count = (max-parseInt(count));
		return count;
	}

}]);