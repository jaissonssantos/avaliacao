app.service('profissionalService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

  var self = this;

  this.set = function(profissional) {
    self.profissional = profissional;
    $rootScope.$broadcast("profissional", profissional);
  };

  this.setIds = function(ids){
    self.ids = ids;
  };

  this.getList = function(){
    $rootScope.$broadcast("profissionais:loading", true);
    $http.post('/controller/plataforma/profissional/list', self.profissional)
    .success(function(profissionais){
      $rootScope.$broadcast("profissionais:loading", false);
      $rootScope.$broadcast("profissionais", profissionais);
    })
    .error(function(profissionais){
      $rootScope.$broadcast("profissionais:loading", false);
      $rootScope.$broadcast("profissionais", profissionais);
    });
  };

  this.load = function(){
    $rootScope.$broadcast("profissional:loading", true);
    $http.post('/controller/plataforma/profissional/get', self.profissional)
    .success(function(profissional){
      $rootScope.$broadcast("profissional:loading", false);
      $rootScope.$broadcast("profissional", profissional);
    })
    .error(function(profissional){
      $rootScope.$broadcast("profissional:loading", false);
      $rootScope.$broadcast("profissional", profissional);
    });
  };

  this.checkEmail = function(profissional){
    data = {
      email: profissional.email,
      id: profissional.id || null
    };
    $rootScope.$broadcast("profissional:email", "loading");
    $http.post('/controller/plataforma/profissional/checkemail', data)
    .success(function(response){
      $rootScope.$broadcast("profissional:email", "found");
    })
    .error(function(response){
      if(response && response.error && response.error == 'Email não cadastrado'){
        $rootScope.$broadcast("profissional:email", "notfound");
      }
    });
  };

  this.checkLogin = function(login){
    $rootScope.$broadcast("profissional:login", "loading");
    $http.post('/controller/plataforma/profissional/checkemail', {login: login})
    .success(function(response){
      $rootScope.$broadcast("profissional:login", "found");
    })
    .error(function(response){
      if(response && response.error == 'Login não cadastrado'){
        $rootScope.$broadcast("profissional:login", "notfound");
      }
    });
  };

  this.save = function(){
    $rootScope.$broadcast("profissional:save", "loading");
    $http.post('/controller/plataforma/profissional/create', self.profissional)
    .success(function(response){
      $rootScope.$broadcast("profissional:save", "success");
      $rootScope.$broadcast("profissionais:message:success", response.success);
      $timeout(function() {
        $rootScope.$broadcast("profissionais:message:success", "");
      }, 5000);
    }).error(function(response){
      $rootScope.$broadcast("profissional:save", "error");
      $rootScope.$broadcast("profissionais:message:error", response.error);
    });
  };

  this.update = function(){
    $rootScope.$broadcast("profissional:update", "loading");
    $http.post('/controller/plataforma/profissional/update', self.profissional)
    .success(function(response){
      $rootScope.$broadcast("profissional:update", "success");
      $rootScope.$broadcast("profissionais:message:success", response.success);
      $timeout(function() {
        $rootScope.$broadcast("profissionais:message:success", "");
      }, 5000);
    }).error(function(response){
      $rootScope.$broadcast("profissional:update", "error");
      $rootScope.$broadcast("profissionais:message:error", response.error);
    });
  };

  this.setStatus = function(status){
    var data = {
      profissionais: self.ids,
      status: status
    };
    $http.post('/controller/plataforma/profissional/setstatus', data)
    .success(function(response){
      $rootScope.$broadcast("profissionais:move", "success");
      self.getList();
      $rootScope.$broadcast("profissionais:message:success", response.success);
      $timeout(function() {
        $rootScope.$broadcast("profissionais:message:success", "");
      }, 5000);
      self.getList();
    }).error(function(error){
      $rootScope.$broadcast("profissionais:move", "error");
    });
  };

}]);
