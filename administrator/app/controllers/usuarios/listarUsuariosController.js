angular.module('app').controller('listarUsuariosController', ['$rootScope', '$scope', '$routeParams', 'usuarioService',
function($rootScope, $scope, $routeParams, usuarioService) {

	$scope.usuarios = $scope.usuario = {};
	$scope.results = {};
	$scope.checkedItens = [];

	$scope.totalItems 	= 0;
	$scope.currentPage 	= 1;
	$scope.numPerPage 	= 10;
	$scope.entryLimit 	= 5;

	$rootScope.$on('usuarios:message:success', function(event, message) {
		$rootScope.success = message;
	});

	$scope.$on("usuarios", function(event, usuarios){
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.totalItems = usuarios.count.results;
		$scope.usuarios = usuarios;
	});

	$scope.$on("usuarios:loading", function(event, status){
		$scope.results.loading = status;
	});

	$scope.setTab = function(statusTab) {
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.currentPage = 1;
		$scope.tab = statusTab;
		$scope.usuario.offset = 0;
		$scope.usuario.limit = $scope.numPerPage;
		$scope.usuario.status = statusTab;
		usuarioService.set($scope.usuario);
		usuarioService.getList();
	};

	$scope.search = function(){
		usuarioService.set($scope.usuario);
		usuarioService.getList();
	};

	$scope.setStatus = function(status){
		usuarioService.setIds($scope.checkedItens);
		usuarioService.setStatus(status);
	};

	$scope.changePaginate = function(){
		$scope.usuario.offset = ($scope.currentPage - 1) * $scope.numPerPage;
		$scope.usuario.limit = $scope.numPerPage;
		usuarioService.getList();
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
		angular.forEach($scope.usuarios.results, function (item) {
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
