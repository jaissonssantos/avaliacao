app.service('lembreteService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

  var self = this;

  this.set = function(lembrete) {
    self.lembrete = lembrete;
    $rootScope.$broadcast("lembrete", lembrete);
  };

  this.load = function(){
    $rootScope.$broadcast("lembrete:loading", true);
    $http.post('controller/lembrete/get', self.lembrete)
    .success(function(lembrete){
      $rootScope.$broadcast("lembrete:loading", false);
      $rootScope.$broadcast("lembrete", lembrete);
    })
    .error(function(lembrete){
      $rootScope.$broadcast("lembrete:loading", false);
      $rootScope.$broadcast("lembrete", lembrete);
    });
  };

  this.update = function(){
    $rootScope.$broadcast("lembrete:save", "loading");
    $http.post('controller/lembrete/update', self.lembrete)
    .success(function(response){
      $rootScope.$broadcast("lembrete:save", "success");
      $rootScope.$broadcast("lembretes:message:success", response.success);
      $timeout(function() {
        $rootScope.$broadcast("lembretes:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("lembrete:save", "error");
      $rootScope.$broadcast("lembretes:message:error", response.error);
    });
  };

}]);
