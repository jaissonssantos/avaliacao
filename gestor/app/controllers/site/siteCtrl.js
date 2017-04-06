'use strict'; 

app
	.controller('siteCtrl', ['$scope', 'agendaSrv', '$locale', '$http', '$routeParams', '$location', '$modal', 'Upload', '$timeout', function($scope, agendaSrv, $locale, $http, $routeParams, $location, $modal, Upload, $timeout){

		$scope.modalItem			= '';

		/*variables*/
		$scope.site 			= {
			submitting: false,
			messagesuccess: false,
			messagewarning: false,
			feedback: undefined
		};
		$scope.redessociais 	= [];
		$scope.profissionais 	= [];
		$scope.profissionaistmp = [];
		$scope.profpage			= [];
		$scope.servpage			= [];
		$scope.servicos 		= [];
		$scope.redesocial		= "";
		$scope.url				= "";

		var $promise = $http.post('/controller/plataforma/site/getredessociais');
		$promise.then(function(item){
			$scope.redessociais = item.data;
		});

		var $promise = $http.post('/controller/plataforma/site/getprofessional');
		$promise.then(function(item){
			$scope.profissionais = item.data;

			var $promise = $http.post('/controller/plataforma/site/getprofissional');
			$promise.then(function(item){
				$scope.profpage = item.data;

				angular.forEach($scope.profissionais, function(value, key) {
					angular.forEach($scope.profpage, function(value2, key2) {
  						if(value.id==value2.idprofissional){
  							$scope.profissionais[key].selected = true;
  						}
  					});
				});
			});

			
		});
		

		var $promise = $http.post('/controller/plataforma/site/getservicos');
		$promise.then(function(item){
			$scope.servicos = item.data;

			var $promise = $http.post('/controller/plataforma/site/getservice');
			$promise.then(function(item){
				$scope.servpage = item.data;

				angular.forEach($scope.servicos, function(value, key) {
					angular.forEach($scope.servpage, function(value2, key2) {
  						if(value.id==value2.idservico){
  							$scope.servicos[key].selected = true;
  						}
  					});
				});
			});
		});

		var $promise = $http.post('/controller/plataforma/site/getsite');
		$promise.then(function(item){
			$scope.site = item.data;
		});


		$scope.checaProfissional = function(index){
			if($scope.profissionais[index].selected){
				$scope.profissionais[index].selected = false;
			}else{
				$scope.profissionais[index].selected = true;
			}
		}

		$scope.checaServico = function(index){
			if($scope.servicos[index].selected){
				$scope.servicos[index].selected = false;
			}else{
				$scope.servicos[index].selected = true;
			}
		}		

		$scope.addredesocial = function(){
			if( $scope.redesocial != "" && $scope.url != "" ){
				$scope.redessociais.push({ tipo: $scope.redesocial, url: $scope.url});
				$scope.redesocial = "";
				$scope.url = "";	
			}
		}
		$scope.removeredesocial = function(index){
 		   $scope.redessociais.splice(index,1);
		}

		$scope.update = function(){

			/*verification invalid*/
			if( $scope.siteForm.$valid ){
				/*show loading e submitting items informations*/
				$scope.site.submitting = true;
				var $promise = Upload.upload({ url: '/controller/plataforma/site/update',  data: {file: $scope.site.image, exibirintroducao: $scope.site.exibirintroducao, introducao: $scope.site.introducao, tituloempresa: $scope.site.tituloempresa, sobre: $scope.site.sobre, tituloprofissional: $scope.site.tituloprofissional, redesocial: $scope.redessociais, servicos: $scope.servicos, profissionais: $scope.profissionais} });
				$promise.then(function(item){
					if( item.data.status == "success" ){
						$scope.site.messagesuccess = true;
					}else if( item.data.status == 'error' ){
						$scope.site.messagewarning = true;
					}
					/*message feedback*/
					$scope.site.feedback = item.data.message;
					/*hidden feedback*/
					$timeout(function() {
				        $scope.site.messagesuccess = false;
				        $scope.site.messagewarning = false;
				    }, 5000);
					/*hidden loading e submitting*/
					$scope.site.submitting = false;
				});
			}
		}
		



	}]);