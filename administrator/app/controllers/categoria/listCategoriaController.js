angular.module('app').controller('listCategoriaController', ['$modal', '$location', '$rootScope', '$scope', '$routeParams', 'categoriaService',
function($modal, $location, $rootScope, $scope, $routeParams, categoriaService) {

	$scope.categorias = $scope.categoria = {};
	$scope.results = {};
	$scope.checkedItens = [];
	$scope.modalItem = "";

	$scope.totalItems 	= 0;
	$scope.currentPage 	= 1;
	$scope.numPerPage 	= 10;
	$scope.entryLimit 	= 5;

	$rootScope.$on('categorias:message:success', function(event, message) {
		$rootScope.success = message;
	});

	$scope.$on("categorias", function(event, categorias){
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.totalItems = categorias.count.results;
		console.log(categorias.count.results);
		$scope.categorias = categorias;
	});

	$scope.$on("categorias:loading", function(event, status){
		$scope.results.loading = status;
	});

	$scope.getList = function(){
		categoriaService.getList();
	};

	$scope.search = function(){
		categoriaService.set($scope.categoria);
		categoriaService.getList();
	};

	$scope.changePaginate = function(){
		$scope.categoria.offset = ($scope.currentPage - 1) * $scope.numPerPage;
		$scope.categoria.limit = $scope.numPerPage;
		categoriaService.getList();
	};

	$scope.editcategoria = function(adv){
		console.log(adv);
			categoriaService.set(adv);
			categoriaService.load();
			console.log($scope.categoria);
			$location.path('/gestor/categorias/add');
		}

	//modal detahes
	$scope.detalhes = function(categoria){
		var modalInstance = $modal.open({
			templateUrl: 'views/categoria/details.html',
			controller: function( $scope, $modalInstance, categoriaRS ){
				$scope.categoria = {};
				$scope.categoria = categoriaRS;
				$scope.cancel = function(){
					$uibModalInstance.dismiss('cancel');
				}
			},
			resolve: {
				categoriaRS: function(){
					return categoria;
				}
			}
		});
	}

	/* confirmação modal para excluir item */
	$scope.deleteconfirm = function(coodenadordelete){
		var modalInstance = $modal.open({
			templateUrl: 'views/confirm.html',
			controller: function ($scope, $modalInstance, categorias) {
				$scope.categoria = categorias;
				$scope.modalItem =  "Deseja realmente excluir o categoria: "+$scope.categoria.nome+"?";
					      	
				$scope.ok = function () {
					$modalInstance.close($scope.categoria);
				};

				$scope.cancel = function () {
					$modalInstance.dismiss('cancel');
				};
			},
			resolve: {
				categorias: function () {
				    return coodenadordelete;
				}
			}
		});
		modalInstance.result.then(function (categoria) {
			categoriaService.set(categoria);
			categoriaService.delete();
			categoriaService.getList();
		}, function () {
		/* funcao ao cancelar ou fechar o modal */
		});

	};

}]);