app.service('publicidadeService', ['$rootScope', '$timeout', '$http' , 'Upload', function ($rootScope, $timeout, $http, $upload) {
  var self = this

  this.set = function (publicidade) {
    self.publicidade = publicidade
    $rootScope.$broadcast('publicidade', publicidade)
  }

  this.setIds = function (ids) {
    self.ids = ids
  }

  this.getList = function () {
    $rootScope.$broadcast('publicidades:loading', true)
    $http.post('/controller/gestor/publicidade/list', self.publicidade)
      .success(function (publicidades) {
        $rootScope.$broadcast('publicidades:loading', false)
        $rootScope.$broadcast('publicidades', publicidades)
      })
      .error(function (publicidades) {
        $rootScope.$broadcast('publicidades:loading', false)
        $rootScope.$broadcast('publicidades', publicidades)
      })
  }

  this.load = function () {
    $rootScope.$broadcast('publicidade:loading', true)
    $http.post('/controller/gestor/publicidade/get', self.publicidade)
      .success(function (publicidade) {
        $rootScope.$broadcast('publicidade:loading', false)
        $rootScope.$broadcast('publicidade', publicidade)
      })
      .error(function (publicidade) {
        $rootScope.$broadcast('publicidade:loading', false)
        $rootScope.$broadcast('publicidade', publicidade)
      })
  }

  this.save = function () {
    $rootScope.$broadcast('publicidade:save', 'loading')
    $http.post('/controller/gestor/publicidade/create', self.publicidade)
      .success(function (response) {
        $rootScope.$broadcast('publicidade:save', 'success')
        $rootScope.$broadcast('publicidades:message:success', 'Cadastrado com sucesso')
        $timeout(function () {
          $rootScope.$broadcast('publicidades:message:success', '')
        }, 3000)
      }).error(function (error) {
      $rootScope.$broadcast('publicidade:save', 'error')
    })
  }

  this.update = function () {
    $rootScope.$broadcast('publicidade:update', 'loading')
    $http.post('/controller/gestor/publicidade/update', self.publicidade)
      .success(function (response) {
        $rootScope.$broadcast('publicidade:update', 'success')
        $rootScope.$broadcast('publicidade:message:success', 'Atualizado com sucesso')
        $timeout(function () {
          $rootScope.$broadcast('publicidade:message:success', '')
        }, 3000)
      }).error(function (error) {
      $rootScope.$broadcast('publicidade:update', 'error')
    })
  }

  this.setStatus = function (status) {
    var data = {
      publicidades: self.ids,
      status: status
    }
    $http.post('/controller/gestor/publicidade/setstatus', data)
      .success(function (response) {
        $rootScope.$broadcast('publicidades:move', 'success')
        self.getList()
        $rootScope.$broadcast('publicidades:message:success', 'Atualizados com sucesso')
        $timeout(function () {
          $rootScope.$broadcast('publicidades:message:success', '')
        }, 3000)
        self.getList()
      }).error(function (error) {
      $rootScope.$broadcast('publicidades:move', 'error')
    })
  }

}])
