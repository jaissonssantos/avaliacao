'use strict';

angular.module('app').controller('panelControlTab', ['$scope', '$routeParams', function($scope, $routeParams){
		$scope.tabshow = $routeParams.tab == 'perfil' || $routeParams.tab == 'agendamentos' ? $routeParams.tab : undefined;
	}])