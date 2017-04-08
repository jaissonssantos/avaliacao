app.service('agendaService', ['$rootScope', '$http', function($rootScope, $http) {

  var self = this;

  this.set = function(agenda) {
    self.agenda = agenda;
    $rootScope.$broadcast("agenda", agenda);
  };

  this.getList = function(){
    $rootScope.$broadcast("agendas:loading", true);
    $http.post('/controller/plataforma/agenda/list', self.agenda)
    .success(function(response){
      $rootScope.$broadcast("agendas:loading", false);
      $rootScope.$broadcast("agendas", response);
    })
    .error(function(response){
      $rootScope.$broadcast("agendas:loading", false);
      $rootScope.$broadcast("agendas", response);
    });
  };

  this.load = function(){
    $rootScope.$broadcast("agenda:loading", true);
    $http.post('/controller/plataforma/agenda/get', self.agenda)
    .success(function(response){
      $rootScope.$broadcast("agenda:loading", false);
      $rootScope.$broadcast("agenda", response);
    })
    .error(function(response){
      $rootScope.$broadcast("agenda:loading", false);
      $rootScope.$broadcast("agenda", response);
    });
  };

  this.receipt = function(){
    $rootScope.$broadcast("agenda:loading", true);
    $http.post('/controller/plataforma/agenda/getreceipt', self.agenda)
    .success(function(response){
      $rootScope.$broadcast("agenda:loading", false);
      $rootScope.$broadcast("agenda", response);
    })
    .error(function(response){
      $rootScope.$broadcast("agenda:loading", false);
      $rootScope.$broadcast("agenda", response);
    });
  };

  this.save = function(){
    $rootScope.$broadcast("agenda:save", "loading");
    $http.post('/controller/plataforma/agenda/create', self.agenda)
    .success(function(response){
      $rootScope.$broadcast("agenda:save", "success");
      $rootScope.$broadcast("agendas:message:success", response);
    }).error(function(response){
      $rootScope.$broadcast("agenda:save", "error");
      $rootScope.$broadcast("agendas:message:error", response.error);
    });
  };

  this.drop = function(parametros){
    $rootScope.$broadcast("agenda:drop", "loading");
    $http.post('/controller/plataforma/agenda/drop', parametros)
    .success(function(response){
      $rootScope.$broadcast("agenda:drop", "success");
      $rootScope.$broadcast("agendas:drop:message:success", response.success);
    }).error(function(response){
      $rootScope.$broadcast("agenda:drop", "error");
      $rootScope.$broadcast("agendas:drop:message:error", response.error);
    });
  };


  this.block = function(){
    $rootScope.$broadcast("agenda:block", "loading");
    $http.post('/controller/plataforma/agenda/block', self.agenda)
    .success(function(response){
      $rootScope.$broadcast("agenda:block", "success");
      $rootScope.$broadcast("agendas:block:message:success", response);
    }).error(function(response){
      $rootScope.$broadcast("agenda:block", "error");
      $rootScope.$broadcast("agendas:block:message:error", response.error);
    });
  };

  this.update = function(){
    $rootScope.$broadcast("agenda:update", "loading");
    $http.post('/controller/plataforma/agenda/update', self.agenda)
    .success(function(response){
      $rootScope.$broadcast("agenda:update", "success");
      $rootScope.$broadcast("agendas:message:success", response.success);
    }).error(function(response){
      $rootScope.$broadcast("agenda:update", response.error);
    });
  };

  this.finalize = function(){
    $rootScope.$broadcast("agenda:finalize", "loading");
    $http.post('/controller/plataforma/agenda/finalize', self.agenda)
    .success(function(response){
      $rootScope.$broadcast("agenda:finalize", "success");
      $rootScope.$broadcast("agendas:message:success", response.success);
    }).error(function(response){
      $rootScope.$broadcast("agenda:finalize", response.error);
    });
  };

  this.getListOfTimes = function(parametros){
    $rootScope.$broadcast("agenda:time", "loading");
    $http.post('/controller/plataforma/agenda/listoftimes', parametros)
    .success(function(response){
      $rootScope.$broadcast("agenda:time", "success");
      $rootScope.$broadcast("horarios", response);
    })
    .error(function(response){
      $rootScope.$broadcast("agenda:time", "error");
      $rootScope.$broadcast("horarios", response);
    });
  };

  this.setStatus = function(){
    $rootScope.$broadcast("agenda:move", "loading");
    $http.post('/controller/plataforma/agenda/setstatus', self.agenda)
    .success(function(response){
      $rootScope.$broadcast("agenda:move", "success");
      $rootScope.$broadcast("agendas:message:success", response.success);
    }).error(function(response){
      $rootScope.$broadcast("agenda:move", "error");
      $rootScope.$broadcast("agendas:message:error", response.error);
    });
  };

}]);
