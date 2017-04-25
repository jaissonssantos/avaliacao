'use stritc';

angular.module('app').controller('companyPageCtrl', ['$scope', '$http', '$routeParams', '$location', '$timeout', function($scope, $http, $routeParams, $location, $timeout){
		

		/*variables*/
		$scope.company = [];
		$scope.contact = [{
			submitting: false,
			messagewarning: false,
			messagesuccess: false,
			messagefeedback: undefined
		}];
		$scope.preloadpage = true;
		$scope.hashcompany = undefined;

		var hashcompany = $routeParams.companyname || undefined;
		if( hashcompany == undefined ){
			$location.path('/404');
		}else{
			$scope.hashcompany = hashcompany;
		}

		$scope.getCompanyPageInfo = function(){
			var $promise = $http.post('/controller/marketplace/getcompanypage', { hashcompany: hashcompany});
			$promise.then(function(item){
				if( item.data[0].page == undefined ){
					$location.path('/company/'+hashcompany)
				}
				$scope.company = item.data[0];
				/*hidden preload in page*/
				$scope.preloadpage = false;
			});
		}

		$scope.sendContact = function(){
			$scope.contact.submitting = true;
			var $promise = $http.post('/controller/marketplace/sendcontactcompanypage', { name: $scope.contact.name, email: $scope.contact.email, subject: $scope.contact.subject, message: $scope.contact.message, sender: $scope.company.email });
			$promise.then(function(item){
				if( item.data.status == 'success' ){
					$scope.contact.messagesuccess = true;
				}else if( item.data.status == 'error' ){
						$scope.contact.messagewarning = true;
				}
				$scope.contact.messagefeedback = item.data.message;
				$scope.contact.submitting = false;
				/*hidden message in 15 seconds*/
				$timeout(function(){
					$scope.contact.messagewarning = false;
					$scope.contact.messagesuccess = false;
				}, 15000);
			});
		}

		/* init get information page in company */
		$scope.getCompanyPageInfo();

	}])