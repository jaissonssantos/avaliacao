app.service('formaPagamentoService', ['$rootScope', '$http', function($rootScope, $http) {

  var self = this;

  this.set = function(formapagamento) {
    self.formapagamento = formapagamento;
    $rootScope.$broadcast("formapagamento", formapagamento);
  };

  this.getList = function(){
    $http.get('/controller/plataforma/formapagamento/list')
    .success(function(formaspagamento){
      $rootScope.$broadcast("formaspagamento", formaspagamento);
    });
  };

  this.save = function(){
    $http.post('/controller/plataforma/formapagamento/create', self.formapagamento)
    .success(function(response){
      $rootScope.$broadcast("formapagamento:save", "success");
    }).error(function(error){
      $rootScope.$broadcast("formapagamento:save", "error");
    });
  };

}]);
