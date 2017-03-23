'use strict';

angular.module('app').controller('panelChangePasswordUserCtrl', ['$scope', '$http', '$location', '$timeout', function($scope, $http, $location, $timeout){

		$scope.user 		= [];
		$scope.submitting   = false;
		$scope.isloading    = false;
		$scope.messagewarning = false;
		$scope.messagesuccess = false;
		$scope.messagefeedbackps = undefined;

		$scope.getInfoProfileUser = function(){
			var $promise = $http.post('/controller/marketplace/getinfoprofileuser');
			$promise.then(function(item){
				$scope.user = item.data[0];
			});
		}

		$scope.savepassword = function(user){
			/*verification invalid*/
			if( $scope.formChangePassword.$valid ){
				$scope.messagewarning = false;
				$scope.messagesuccess = false;
				/*verification password new is iguals password confirm*/
				if( user.passwordnew != user.passwordconfirmnew ){
					$scope.messagewarning = true;
					$scope.messagefeedbackps = 'Senhas não coincidem, favor verifique';
				}else{
					/*show loading e submitting items informations user*/
					$scope.submitting = true;
					$scope.isloading = true;
					var $promise = $http.post('/controller/marketplace/updatepasswordprofileuser', {passwordold: user.passwordold, passwordnew: user.passwordnew});
					$promise.then(function(item){
						if( item.data.status == 'success' ){
							$scope.messagesuccess = true;
							/*clear items user*/
							user.passwordold = null;
							user.passwordnew = null; 
							user.passwordconfirmnew = null;
						}else if( item.data.status == 'error' ){
							$scope.messagewarning = true;
						}
						/*message feedback*/
						$scope.messagefeedbackps = item.data.message;
						/*hidden loading e submitting*/
						$scope.submitting = false;
						$scope.isloading = false;
						/*hidden message in 5 secondes*/
						$timeout(function(){
							$scope.messagesuccess = false;
							$scope.messagewarning = false;
						}, 5000);
					});
				}
			}
		}

		/*init function call*/
			/*get information user*/
			// $scope.getInfoProfileUser();

	}]);