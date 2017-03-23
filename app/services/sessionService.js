'use strict';

angular.module('app').factory('sessionService', ['$http', '$window', function( $http, $window ){
		return {
			
			set: function(key, value){
				return $window.sessionStorage.setItem(key, value);
			},

			get: function(key){
				return $window.sessionStorage.getItem(key);
			},

			destroy: function(key){
				return $window.sessionStorage.removeItem(key);
			}

		};
	}]);