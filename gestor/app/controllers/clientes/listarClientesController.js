angular.module('app').controller('listarClientesController', ['$rootScope', '$scope', '$routeParams', 'clienteService',
function($rootScope, $scope, $routeParams, clienteService) {

	$scope.clientes = $scope.cliente = {};
	$scope.results = {};
	$scope.checkedItens = [];

	$scope.totalItems 	= 0;
	$scope.currentPage 	= 1;
	$scope.numPerPage 	= 10;
	$scope.entryLimit 	= 5;

	$rootScope.$on('clientes:message:success', function(event, message) {
		$rootScope.success = message;
	});

	$scope.$on("clientes", function(event, clientes){
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.totalItems = clientes.count.results;
		$scope.clientes = clientes;
	});

	$scope.$on("clientes:loading", function(event, status){
		$scope.results.loading = status;
	});

	$scope.setTab = function(statusTab) {
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.currentPage = 1;
		$scope.tab = statusTab;
		$scope.cliente.offset = 0;
		$scope.cliente.limit = $scope.numPerPage;
		$scope.cliente.status = statusTab;
		clienteService.set($scope.cliente);
		clienteService.getList();
	};

	$scope.search = function(){
		clienteService.set($scope.cliente);
		clienteService.getList();
	};

	$scope.setStatus = function(status){
		clienteService.setIds($scope.checkedItens);
		clienteService.setStatus(status);
	};

	$scope.changePaginate = function(){
		$scope.cliente.offset = ($scope.currentPage - 1) * $scope.numPerPage;
		$scope.cliente.limit = $scope.numPerPage;
		clienteService.getList();
	};

	$scope.checkItem = function (item) {
		if(item.selected){
			$scope.checkedItens.push(item.id);
		}else{
			var index = $scope.checkedItens.indexOf(item.id);
			$scope.checkedItens.splice(index, 1);
		}
	};

	$scope.checkAll = function () {
		$scope.selectedAll = !$scope.selectedAll;
		angular.forEach($scope.clientes.results, function (item) {
			item.selected = $scope.selectedAll;
			if(item.selected){
				$scope.checkedItens.push(item.id);
			}else{
				var index = $scope.checkedItens.indexOf(item.id);
				$scope.checkedItens.splice(index, 1);
			}
		});
	};
}]);
