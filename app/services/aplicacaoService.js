angular.module('app').service('aplicacaoService', ['$rootScope','$timeout','$http', 
  function ($rootScope, $timeout, $http) {    
    
    var self = this;
    this.set = function (aplicacao) {
      self.aplicacao = aplicacao
      $rootScope.$broadcast('aplicacao', aplicacao)
    }

    this.getDate = function () {
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/marketplace/aplicacao/getdate', self.aplicacao)
        .success(function (response) {
          $rootScope.$broadcast('aplicacao:loading', false)
          $rootScope.$broadcast('aplicacao', response)
        })
        .error(function (response) {
          $rootScope.$broadcast('aplicacao:loading', false)
          $rootScope.$broadcast('aplicacao', response)
        })
    };

    this.login = function(email,password){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/marketplace/aplicacao/login', {email:email, password:password})
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:login', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:login', response)
      });
    };

    this.logout = function(){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/marketplace/aplicacao/logout', self.aplicacao)
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:logout', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:logout', response)
      });
    };

    this.signup = function(name,phone,email,password){
      $rootScope.$broadcast("aplicacao:signup", "loading");
      $http.post('/controller/marketplace/aplicacao/signup', {name: name, phone: phone, email:email, password:password})
      .success(function(response){
        $rootScope.$broadcast("aplicacao:signup", "success");
        $rootScope.$broadcast("aplicacao:signup:message:success", response);
      }).error(function(response){
        $rootScope.$broadcast("aplicacao:signup", "error");
        $rootScope.$broadcast("aplicacao:signup:message:error", response.error);
        $timeout(function(){
            $rootScope.$broadcast("aplicacao:signup:message:error", "");
        }, 5000);
      });
    };

    this.checkLogin = function () {
      $rootScope.$broadcast('aplicacao:checklogin:loading', true)
      $http.post('/controller/marketplace/aplicacao/checklogin', self.aplicacao)
        .success(function (response) {
          $rootScope.$broadcast('aplicacao:checklogin:loading', false)
          $rootScope.$broadcast('aplicacao:checklogin', response)
        })
        .error(function (response) {
          $rootScope.$broadcast('aplicacao:checklogin:loading', false)
          $rootScope.$broadcast('aplicacao:checklogin', response)
        })
    };

    this.password = function(email){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/marketplace/aplicacao/password', {email:email})
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:password', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:password', response)
      });
    };

    this.updatepassword = function(token,password){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/marketplace/aplicacao/updatepassword', {token:token, password:password})
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:updatepassword', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:updatepassword', response)
      });
    };

    this.checkToken = function (token) {
      $rootScope.$broadcast('aplicacao:checktoken:loading', true)
      $http.post('/controller/marketplace/aplicacao/checktoken', {token:token})
        .success(function (response) {
          $rootScope.$broadcast('aplicacao:checktoken:loading', false)
          $rootScope.$broadcast('aplicacao:checktoken', response)
        })
        .error(function (response) {
          $rootScope.$broadcast('aplicacao:checktoken:loading', false)
          $rootScope.$broadcast('aplicacao:checktoken', response)
        })
    };
}])

.factory('clienteFactory', ['$rootScope',function($rootScope){
    var cliente = [];
    cliente.get = function(){
      return this.cliente;
    }
    cliente.set = function(cliente){
      if(cliente.results){
        this.cliente = cliente.results;
      }else{
        this.cliente = cliente;
      }
      this.broadcastCliente();
    }
    cliente.broadcastCliente = function(){
      $rootScope.$broadcast('handleBroadcast');
    }
    return cliente;
}])

.factory('sessionFactory', ['$window',function($window){
  return {
    set: function(key,value){
      return $window.sessionStorage.setItem(key,value);
    },
    get: function(key){
      return $window.sessionStorage.getItem(key);
    },
    destroy: function(key){
      return $window.sessionStorage.removeItem(key);
    }
  };
}])

.factory('calendarioFactory', ['$rootScope',function($rootScope){
  return {
    getMonthDescription: function(month){
      var m = '';
      switch(parseInt(month)){
        case 1: m = 'Janeiro'; break;
        case 2: m = 'Fevereiro'; break;
        case 3: m = 'Março'; break;
        case 4: m = 'Abril'; break;
        case 5: m = 'Maio'; break;
        case 6: m = 'Junho'; break;
        case 7: m = 'Julho'; break;
        case 8: m = 'Agosto'; break;
        case 9: m = 'Setembro'; break;
        case 10: m = 'Outubro'; break;
        case 11: m = 'Novembro'; break;
        case 12: m = 'Dezembro'; break;
      }
      return m;
    },
    getDaysInMonth: function(year,month,day){
      month = (parseInt(month)-1);
      var days = [];
      var start = new Date(year, month , day);
      var end = new Date(year, month +1 , day);
      while (start <= end) {
          days.push(new Date(start));
          start.setDate(start.getDate() + 1);
      }
      return days;
    },
    getDisabledDaySun: function(day,daywork){
      if( day == null ){
        return "";
      }
      var retorno = 0;
      if(!daywork || !daywork.length) return;
      for(var i = 0; i < daywork.length; i++){
        if( parseInt(day) == parseInt(daywork[i].dia) ){
          retorno++;
        }
      }
      if( retorno == 0 ){/*sunday = 0 in days array*/
        return true;
      }
      return false;
    },
    getComparedDateCurrentDate: function(dateweek,dateserver){
      if( dateweek == null || dateserver == null )
        return '';
      if( dateweek - dateserver == 0 ){
        return true;
      }else{

      }
      return false;
    }
  };
}])

.factory('geoFactory', ['$q','$window',function($q,$window){
  return function() {
    var deferred = $q.defer();
    if(!$window.navigator.geolocation) {
      deferred.reject(new Error('Geolocation is not supported'));
    } else {
      console.log($window.navigator.geolocation);
      $window.navigator.geolocation.getCurrentPosition(function(position) {
        deferred.resolve({
          lat: position.coords.latitude,
          lng: position.coords.longitude
        });
      }, deferred.reject);
    }
    return deferred.promise;
  }
}])

.factory('zipCodeFactory', ['$q','$http','geoFactory',function($q,$http,geoFactory){
  var MAPS_ENDPOINT = 'http://maps.google.com/maps/api/geocode/json?latlng={POSITION}&sensor=false';
  return {
      urlForLatLng: function(lat, lng) {
        return MAPS_ENDPOINT.replace('{POSITION}', lat + ',' + lng);
      },
      lookupByLatLng: function(lat, lng) {
        var deferred = $q.defer();
        var url = this.urlForLatLng(lat, lng);
        $http.get(url).success(function(response) {
          // hacky
          var zipCode;
          angular.forEach(response.results, function(result) {
            if(result.types[0] === 'postal_code') {
              zipCode = result.address_components[0].short_name;
            }
          });
          deferred.resolve(zipCode);
        }).error(deferred.reject);
        return deferred.promise;
      },
      lookup: function() {
        var deferred = $q.defer();
        var self = this;
        geoFactory().then(function(position) {
          deferred.resolve(self.lookupByLatLng(position.lat, position.lng));
        }, deferred.reject);
        return deferred.promise;
      }
  };
}])
