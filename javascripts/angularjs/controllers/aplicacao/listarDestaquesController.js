angular.module('app').controller('listarDestaquesController', ['$rootScope', '$scope', '$routeParams', 'destaqueService',
function($rootScope, $scope, $routeParams, destaqueService) {

	$scope.destaques = []; 
	$scope.destaque = {};
	$scope.results = {};

	//$scope.totalItems 	= 0;
	$scope.currentPage 	= 1;
	$scope.numPerPage 	= 10;
	$scope.entryLimit 	= 5;

	$rootScope.$on('destaques:message:success', function(event, message) {
		$rootScope.success = message;
	});

	$rootScope.$on("destaques:list", function(event, destaques){
		//$scope.totalItems = destaques.count.results;
		$scope.destaques = destaques.results;
	});

	$rootScope.$on("destaques:loading", function(event, status){
		$scope.results.loading = status;
	});

    $scope.load = function() {
		$scope.currentPage = 1;
		$scope.destaque.offset = 0;
        $scope.destaque.segment = $routeParams.segment;
		$scope.destaque.limit = $scope.numPerPage;
		destaqueService.set($scope.destaque);
        destaqueService.getList();
    };

	$scope.search = function(){
		destaqueService.set($scope.destaque);
		destaqueService.getList();
	};

	$scope.changePaginate = function(){
		$scope.destaque.offset = ($scope.currentPage - 1) * $scope.numPerPage;
		$scope.destaque.limit = $scope.numPerPage;
		destaqueService.getList();
	};

}]);
