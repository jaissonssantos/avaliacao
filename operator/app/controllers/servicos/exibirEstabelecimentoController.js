
angular.module('app').controller('exibirEstabelecimentoController', ['$rootScope', '$scope', '$routeParams', 'estabelecimentoService', function($scope, $rootScope, $routeParams, estabelecimentoService){

	$rootScope.$on("estabelecimento", function(event, estabelecimento){
		$scope.estabelecimento = estabelecimento;
	});

	estabelecimentoService.loadEstabelecimento($routeParams.hash);

}]);
