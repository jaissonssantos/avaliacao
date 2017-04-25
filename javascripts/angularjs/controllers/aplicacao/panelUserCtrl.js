'use strict';

angular.module('app').controller('panelUserCtrl', ['$scope', '$http', 'authenticateMarketplaceSrv', '$location', function($scope, $http, authenticateMarketplaceSrv, $location){

		$scope.islogged = function(){
			var $promise = authenticateMarketplaceSrv.isLogged();
			$promise.then(function(item){
				if( item.data.status == 'error' ){
					$location.path('/login');
				}
			});
		}

		/*init controller function*/
			/*is logged user*/
			$scope.islogged();

	}]);