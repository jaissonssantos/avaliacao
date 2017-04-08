'use strict'; 

app
	.controller('contatoClientesCtrl', ['$scope', '$locale', '$http', '$routeParams', '$location', function($scope, $locale, $http, $routeParams, $location){

		$scope.clientesestabelecimento = [];
		var $promise = $http.post('/controller/plataforma/agenda/getclientes');
		$promise.then(function(item){
			$scope.clientesestabelecimento = item.data;
		});

}]);

