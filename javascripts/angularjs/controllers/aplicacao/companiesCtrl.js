'use strict'; 

app
	.controller('companiesCtrl', ['$scope', '$http', '$routeParams', '$location', 'ngProgressFactory',  function($scope, $http, $routeParams, $location, ngProgressFactory){
		
		/*loading page start*/
		$scope.ngProgressApp = ngProgressFactory.createInstance();
		$scope.ngProgressApp.start();

		/*variables*/
		$scope.hashsegment = $routeParams.hashsegment || undefined;
		$scope.companies = [];
		$scope.loaderfilter = undefined;
		$scope.subfilter = {
			discount:  false,
			payment:  0
		}

		/*params for paginator*/
		$scope.totalItems 	= 0;
		$scope.currentPage 	= 1; //current page
		$scope.numPerPage 	= 10; //number item show container
		$scope.entryLimit 	= 5; //max no of items to display in a page in pagination

		/*functions paginate companies*/
		$scope.paginate = function(value) {
			var begin, end, index;
			begin = ($scope.currentPage - 1) * $scope.numPerPage;
			end = begin + $scope.numPerPage;
			index = $scope.companies.indexOf(value); //index in companies
			return (begin <= index && index < end);
		}
		$scope.changePaginate = function(){
			$scope.getCompanysFilter();
		}

		/*Range slider config*/
	    $scope.minRangeSlider = {
	        minValue: 0,
	        maxValue: 10,
	        options: {
	            ceil: 500,
            	floor: 0,
	            translate: function (value) {
	                return 'R$' + value;
	            },
	            onEnd: function () {
	                $scope.filterRangeOfValues($scope.minRangeSlider.minValue, $scope.minRangeSlider.maxValue);
	            }
	        }
	    }

		$scope.getCompanysBegin = function(){
			/*show loader companies*/
			$scope.loaderfilter = true;
			var $limitmin = ($scope.currentPage - 1) * $scope.numPerPage;
			var $limitmax = $limitmin + $scope.numPerPage;
			var $promise = $http.post('/controller/marketplace/getcompanies', {hashsegment: $scope.hashsegment, limitmin: $limitmin, limitmax: $limitmax});
			$promise.then(function(item){
				$scope.companies = [];
				$scope.companies = item.data.company;
				$scope.totalItems = (item.data == '' || item.data == undefined  ? 0 : item.data.total ); //total items 
				/*hidden loader companies*/
				$scope.loaderfilter = false;
			});
		}

		$scope.getCompanysFilter = function(){
			/*show loader companies*/
			$scope.loaderfilter = true;
			var $limitmin = ($scope.currentPage - 1) * $scope.numPerPage;
			var $limitmax = $limitmin + $scope.numPerPage;
			var $promise = $http.post('/controller/marketplace/getcompanies', {hashsegment: $scope.hashsegment, limitmin: $limitmin, limitmax: $limitmax});
			$promise.then(function(item){
				var $item = [];
				var $exists = 0;
				$item = item.data.company;
				for( var $x = 0; $x < $item.length; $x++){
					for( var $i = 0; $i < $scope.companies.length; $i++ ){
						if( $item[$x].hash == $scope.companies[$i].hash ){ //verification exists item in object
							$exists++;
						}
					}
					if( $exists == 0 ){
						$scope.companies.push($item[$x]); //push last item paginator
					}
					$exists = 0;
				}
				/*hidden loader companies*/
				$scope.loaderfilter = false;
			});
		}

		$scope.getMinMaxServices = function(){
			var $promise = $http.post('/controller/marketplace/getminmaxservices');
			$promise.then(function(item){
				$scope.minRangeSlider.maxValue = item.data[0].valormaximo;
				$scope.minRangeSlider.options.ceil = item.data[0].valormaximo;
				/*loading page complete*/
				$scope.ngProgressApp.complete();
			});
		}

		$scope.filterDiscount = function(){
			$scope.filterRangeOfValues();
		}

		$scope.filterPayment = function(){
			$scope.filterRangeOfValues();
		}

		$scope.filterRangeOfValues = function(min, max){
			//clear companies
			$scope.companies = [];
			var $min = (min == undefined ? $scope.minRangeSlider.minValue : min );
			var $max = (max == undefined ? $scope.minRangeSlider.maxValue : max );
			/*show loader companies*/
			$scope.loaderfilter = true;
			var $limitmin = ($scope.currentPage - 1) * $scope.numPerPage;
			var $limitmax = $limitmin + $scope.numPerPage;
	        var $promise = $http.post('/controller/marketplace/getcompaniesfilter', {valorminimo: $min, valormaximo: $max, desconto: $scope.subfilter.discount, pagamento: $scope.subfilter.payment, hashsegment: $scope.hashsegment, limitmin: $limitmin, limitmax: $limitmax });
	        $promise.then(function(item){
	        	/*clear current companies*/
	        	var $item = [];
	        	$item = item.data.company;
	        	$scope.companies = $item;
	        	/*update total items*/
	        	$scope.totalItems = (item.data == '' || item.data == undefined  ? 0 : $scope.companies.length ); //total items 
	        	$scope.loaderfilter = false;
	        });
		}

		/*init function call*/
			/*get values services min and max*/
			$scope.getMinMaxServices();

	}])