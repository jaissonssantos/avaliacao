'use strict';

app
	.controller('passwordresetUserCtrl', ['$scope', '$http', 'authenticateMarketplaceSrv', function($scope, $http, authenticateMarketplaceSrv){

		$scope.messagefeedbackps 		= undefined;
		$scope.messagewarning 			= false;
		$scope.messagesuccess 			= false;
		$scope.user 					= [];
		$scope.isloading 				= false;
		$scope.submitting 				= false;

		$scope.passwordreset = function( user ){
			if( $scope.formPasswordReset.$valid ){
				authenticateMarketplaceSrv.passwordreset( user, $scope );
			}
		}

	}]);