app.service('dashboardService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

  var self = this;

  this.set = function(painel) {
    self.painel = painel;
    $rootScope.$broadcast("painel", painel);
  };

  this.load = function(){
    $rootScope.$broadcast("painel:loading", true);
    $http.post('/controller/plataforma/getinfodashboard', self.painel)
    .success(function(item){
      $rootScope.$broadcast("painel:loading", false);
      $rootScope.$broadcast("painel", item);
    })
    .error(function(item){
      $rootScope.$broadcast("painel:loading", false);
      $rootScope.$broadcast("painel", item);
    });
  };


}]);
