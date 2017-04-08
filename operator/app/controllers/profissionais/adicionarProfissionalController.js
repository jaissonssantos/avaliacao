
angular.module('app').controller('adicionarProfissionalController', ['$location', '$rootScope', '$scope', '$routeParams', 'profissionalService', function($location, $rootScope, $scope, $routeParams, profissionalService){

	$scope.profissional = {};
	$scope.profissional.diastrabalho = [];

	$rootScope.$on('profissionais:message:error', function(event, message) {
		$rootScope.error = message;
	});

	$rootScope.$on('profissional:save', function(event, status) {
	    $scope.status = {
	      loading: (status == 'loading'),
	      success: (status == 'success'),
	      error: (status == 'error')
	    };
		if($scope.status.success){
			$location.path('configuracoes/list/staff/');
		}
  	});

	$scope.save = function() {
		profissionalService.set($scope.profissional);
		profissionalService.save();
	};

	$scope.addDiaTrabalho = function(dia){
		var isset = $scope.profissional.diastrabalho.filter(function(diatrabalho){
			return diatrabalho.dia === dia;
		});
		if(isset.length)
			return;
		$scope.profissional.diastrabalho.push({dia:dia});
	};

	$scope.delDiaTrabalho = function(dia){
		$scope.profissional.diastrabalho.forEach(function(diatrabalho, index){
			if(diatrabalho.dia == dia) $scope.profissional.diastrabalho.splice(index, 1);
		});
	};

}]);
