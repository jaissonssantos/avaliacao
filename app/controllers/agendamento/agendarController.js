function agendarController ($scope, $http, $routeParams, $location, $timeout, ngProgressFactory, aplicacaoService, calendarioFactory, clienteFactory, agendamentoService, estabelecimentoService) {
  $scope.ngProgressApp = ngProgressFactory.createInstance()
  $scope.ngProgressApp.start()

  // Definindo variáveis
  $scope = angular.extend($scope, {
    agendamento: {
      hash: $routeParams.hash || undefined,
      service: $routeParams.service || undefined,
      reserve: undefined,
      pay: {
        method: 2
      }
    },
    cliente: {},
    clients: {},
    estabelecimento: {},
    aplicacao: {},
    ocupados: {},
    results: {
      formulario: {},
      agendamento: {}
    },
    calendario: {
      semanaExtenso: 'esta semana',
      dataInativo: undefined,
      horarios: []
    },
    countWeeknext: 0,
    positioncurrent: 0,
    positionprev: 0,
    stageTwoViewForm: 'signup' || undefined,
    week: [],
    daySun: 0,
    descMonth: '',
    descYear: '',
    descWeek: 'esta semana',
    isloading: false,
    isagenda: false,
    scheduling: [],
    daywork: [],
    commitmentmarked: [],
    timePicker: [],
    timePickerAvailable: false,
    activeweekmanual: undefined,
    activeweeknotdaymanual: undefined,
    step: 0,
    signupuser: [],
    loginuser: [],
    checkautenticateuser: false,
    paysubmitting: false,
    card: [],
    serverDate: undefined
  })

  var hashscheduled = $routeParams.hashscheduled || undefined
  var hashservice = $routeParams.hashservice || undefined
  var hashcompany = $scope.hashcompanyname = $routeParams.companyname || undefined
  if ($scope.agendamento.hash == undefined && $scope.agendamento.service == undefined) {
    $location.path('/404')
  }

  $scope.$on('handleBroadcast', function () {
    $scope.clients = clienteFactory.get()
  })

  $scope.$on('estabelecimento:favorite', function (event, status) {
    $scope.status_favorite = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    }
    if ($scope.status_favorite.loading)
      $scope.ngProgressApp.start()
    if ($scope.status_favorite.success || $scope.status_favorite.error)
      $scope.ngProgressApp.complete()
  })

  $scope.$on('estabelecimentos:favorite:message:success', function (event, message) {
    $scope.success_favorite = message.success
    $timeout(function () {
      $scope.success_favorite = ''
    }, 5000)
    if (message.favorite) $scope.estabelecimento.favorito = true
    if (!message.favorite) $scope.estabelecimento.favorito = false
  })

  $scope.$on('estabelecimentos:favorite:message:error', function (event, message) {
    $scope.error_favorite = message.error
  })

  $scope.$on('agendamento:save', function (event, status) {
    $scope.status = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    }
  })

  $scope.$on('agendamentos:message:success', function (event, message) {
    $scope.success = message
  })

  $scope.$on('agendamentos:message:error', function (event, message) {
    $scope.error = message
  })

  $scope.$on('agendamento', function (event, agendamento) {
	
    $scope.estabelecimento = agendamento.results
    if (agendamento.results == undefined || $scope.estabelecimento.profissional.length == 0) {
      return
    }
    $scope.agendamento.profissional = $scope.estabelecimento.profissional[0]
    $scope.agendamento.idestabelecimento = $scope.estabelecimento.id
    $scope.agendamento.servico = $scope.estabelecimento.servico
    $scope.agendamento.estabelecimento = $scope.estabelecimento.nomefantasia
    $scope.agendamento.onde = (
      $scope.estabelecimento.logradouro + ',' +
      ($scope.estabelecimento.complemento == null ? '' : $scope.estabelecimento.complemento) + ' ' +
      $scope.estabelecimento.numero + ' ' +
      $scope.estabelecimento.bairro + ', ' +
      $scope.estabelecimento.cidade + ', ' +
      $scope.estabelecimento.estado + ', ' +
      $scope.estabelecimento.cep
    )
  })

  $scope.$on('agendamento:loading', function (event, status) {
    $scope.results.agendamento.loading = status
  })

  $scope.$on('agendamento:horario:ocupados', function (event, ocupados) {
    $scope.ocupados = ocupados.results
    $scope.agendamento.reserve = undefined
    $scope.calendario.horarios = []
    $scope.calendar()
  })

  $scope.$on('agendamento:horario:ocupados:loading', function (event, status) {
    $scope.results.agendamento.loading = status
  })

  $scope.$on('aplicacao', function (event, aplicacao) {
    $scope.aplicacao = aplicacao.results
    if ($scope.aplicacao.ano) {
      $scope.calendario.data = new Date($scope.aplicacao.ano, $scope.aplicacao.mes - 1, $scope.aplicacao.dia)
      $scope.calendario.horarioLimite = new Date($scope.aplicacao.ano, $scope.aplicacao.mes - 1, $scope.aplicacao.dia, $scope.aplicacao.hora, $scope.aplicacao.minuto)
      $scope.calendario.semana = calendarioFactory.getDaysInMonth($scope.aplicacao.ano, $scope.aplicacao.mes, $scope.aplicacao.dia)
      $scope.calendario.mes = calendarioFactory.getMonthDescription($scope.aplicacao.mes)
      $scope.calendario.ano = $scope.aplicacao.ano

      $scope.agendamento.date = $scope.calendario.data
      agendamentoService.getBusyTime()
    }
  })

  $scope.$on('aplicacao:signup:message:success', function (event, message) {
    if (message.results) clienteFactory.set(message.results)
    $scope.stage = 3
  })

  $scope.$on('aplicacao:signup:message:error', function (event, message) {
    $scope.error_signup = message
  })

  $scope.$on('aplicacao:signup', function (event, status) {
    $scope.status_signup = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    }
    if ($scope.status_signup.success)
      $scope.cliente = clienteFactory.get()
  })

  $scope.$on('aplicacao:loading', function (event, status) {
    $scope.results.formulario.loading = status
  })

  $scope.$on('aplicacao:login', function (event, aplicacao) {
    if (aplicacao.error) {
      $scope.error_login = aplicacao.error
      $timeout(function () {
        $scope.error_login = ''
      }, 5000)
    }
    if (aplicacao.results) {
      clienteFactory.set(aplicacao.results)
      $scope.stage = 3
    }
  })

  $scope.$on('aplicacao:password', function (event, aplicacao) {
    if (aplicacao.error) {
      $scope.error_password = aplicacao.error
      $timeout(function () {
        $scope.error = ''
      }, 5000)
    }
    if (aplicacao.success) $scope.success_password = aplicacao.success
    $scope.cliente.email = undefined
  })

  $scope.load = function () {
    agendamentoService.set($scope.agendamento)
    agendamentoService.load()
    aplicacaoService.getDate()
  }

  $scope.favorite = function () {
    estabelecimentoService.favorite($scope.estabelecimento.id)
  }

  $scope.calendarWeekDay = function (date) {
    var count = 0
    if (calendarioFactory.getDisabledDaySun(date.getDay, $scope.agendamento.profissional.atendimento))
      count++
    if (count) {
      $scope.calendario.data = date, $scope.agendamento.date = date
      agendamentoService.getBusyTime()
    }
  }

  $scope.calendarTimeDay = function (time) {
    $scope.agendamento.reserve = time + ''
    var reserveend = new Date($scope.agendamento.reserve)
    reserveend.setMinutes(reserveend.getMinutes() + parseInt($scope.estabelecimento.servico.duracao))
    $scope.agendamento.reserveend = reserveend
  }

  $scope.calendarEdit = function () {
    $scope.stage = 0
  }

  $scope.calendar = function () {
    var dia = $scope.calendario.data.getDay()
    var horarioInicio = undefined, horarioFim = undefined
    var dataCount = 0, dataFeriado = 0

	if(!$scope.agendamento.profissional || !$scope.agendamento.profissional.atendimento) {
		return
	}

    for (var i = 0;i < $scope.agendamento.profissional.atendimento.length;i++) {
      if (parseInt(dia) == parseInt($scope.agendamento.profissional.atendimento[i].dia)) {
        dataCount++
        horarioInicio = $scope.agendamento.profissional.atendimento[i].horainicial
        horarioFim = $scope.agendamento.profissional.atendimento[i].horafinal
      }
    }

    if (parseInt(dia) == 0 && dataCount == 0) {
      dia = parseInt(dia) + 1
      for (var i = 0;i < $scope.agendamento.profissional.atendimento.length;i++) {
        if (parseInt(dia) == parseInt($scope.agendamento.profissional.atendimento[i].dia)) {
          dataCount++
          horarioInicio = $scope.agendamento.profissional.atendimento[i].horainicial
          horarioFim = $scope.agendamento.profissional.atendimento[i].horafinal

          $scope.calendario.data.setDate($scope.calendario.data.getDate() + 1)
        }
      }
    }

    if (dataCount == 0) {
      var proximoDataAtendimento = []
      for (var i = 0;i < $scope.calendario.semana.length;i++) {
        if ($scope.agendamento.profissional.atendimento[0].dia == $scope.calendario.semana[i].getDay()) {
          proximoDataAtendimento.push({'dia': $scope.calendario.semana[i]})
        }
      }
      dataCount++
      horarioInicio = $scope.agendamento.profissional.atendimento[0].horainicial
      horarioFim = $scope.agendamento.profissional.atendimento[0].horafinal
      $scope.calendario.dataInativo = proximoDataAtendimento[0].dia
      dia = $scope.calendario.dataInativo.getDate()
    }

    if (dataCount) {
      var dta_inicio = new Date(($scope.calendario.data.getMonth() + 1) + '/' + $scope.calendario.data.getDate() + '/' + $scope.calendario.data.getFullYear() + ' ' + horarioInicio); /*format "mm/dd/yyyy hh:mm:ss"*/
      var dta_fim = new Date(($scope.calendario.data.getMonth() + 1) + '/' + $scope.calendario.data.getDate() + '/' + $scope.calendario.data.getFullYear() + ' ' + horarioFim); /*format "mm/dd/yyyy hh:mm:ss"*/
      while(dta_inicio <= dta_fim){
        var minuto = (((dta_inicio.getMinutes() + 7.5) / 15 | 0) * 15) % 60
        var hora = ((((dta_inicio.getMinutes() / 105) + .5) | 0) + dta_inicio.getHours()) % 24
        var horario = new Date(dta_inicio)
        horario.setHours(hora)
        horario.setMinutes(minuto)
        if ($scope.calendario.horarioLimite > horario) {
          $scope.calendario.horarios.push({'horario': horario,'horarioLimite': true})
        }else {
          $scope.calendario.horarios.push({'horario': horario,'horarioLimite': false})
        }
        // adiciona o intervalo da duracao de atendimento
        dta_inicio.setMinutes(dta_inicio.getMinutes() + parseInt($scope.estabelecimento.servico.duracao))
      }
    }

    if ($scope.ocupados != undefined) {
      for (var i = 0;i < $scope.ocupados.length;i++) {
        var ocupado_inicio = new Date($scope.ocupados[i].horainicial)
        var ocupado_fim = new Date($scope.ocupados[i].horafinal)

        for (var x = 0;x < $scope.calendario.horarios.length;x++) {
          var tmphorario = new Date($scope.calendario.horarios[x].horario)
          tmphorario.setMinutes(tmphorario.getMinutes() + 1)
          if (tmphorario >= ocupado_inicio && tmphorario <= ocupado_fim) {
            // fazer a parte do editar agendamento
            $scope.calendario.horarios[x].horario = undefined
          }
        }
      }
    }
    $scope.ngProgressApp.complete()
  }

  $scope.changeProfessional = function () {
    aplicacaoService.getDate()
    $scope.stage = 0
  }

  $scope.calendarAction = function () {
    if ($scope.clients.id)
      $scope.stage = 3
    else
      $scope.stage = 2
  }

  $scope.save = function () {
    agendamentoService.save()
  }

  $scope.signup = function () {
    aplicacaoService.signup($scope.cliente.nome, $scope.cliente.celular, $scope.cliente.email, $scope.cliente.senha)
  }

  $scope.login = function () {
    aplicacaoService.login($scope.cliente.email, $scope.cliente.senha)
  }

  $scope.password = function () {
    aplicacaoService.password($scope.cliente.email)
  }

  $scope.viewForm = function (view) {
    $scope.stageTwoViewForm = view
  }

  $scope.disabledSun = function (day, daywork) {
    var result = calendarioFactory.getDisabledDaySun(day, daywork)
    return result
  }

  $scope.comparedCurrentDate = function (dateweek, dateserver) {
    var result = calendarioFactory.getComparedDateCurrentDate(dateweek, dateserver)
    return result
  }

  $scope.changeDescWeek = function (title) {
    $scope.$apply(function () {
      $scope.calendario.semanaExtenso = title
    })
  }
}

agendarController.$inject = ['$scope', '$http', '$routeParams', '$location', '$timeout', 'ngProgressFactory', 'aplicacaoService', 'calendarioFactory', 'clienteFactory', 'agendamentoService', 'estabelecimentoService']
angular.module('app').controller('agendarController', agendarController)
