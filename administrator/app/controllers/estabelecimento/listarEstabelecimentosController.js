angular.module('app').controller('listarEstabelecimentosController', ['$rootScope', '$scope', '$routeParams', 'estabelecimentoService',
    function($rootScope, $scope, $routeParams, estabelecimentoService) {

        $scope.estabelecimentos = $scope.estabelecimento = {};
        $scope.results = {};
        $scope.checkedItens = [];

        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.numPerPage = 10;
        $scope.entryLimit = 5;

        $rootScope.$on('estabelecimentos:message:success', function(event, message) {
            $rootScope.success = message;
        });

        $scope.$on("estabelecimentos", function(event, estabelecimentos) {
            $scope.checkedItens = [];
            $scope.selectedAll = false;
            $scope.totalItems = estabelecimentos.count.results;
            $scope.estabelecimentos = estabelecimentos;
        });

        $scope.$on("estabelecimentos:loading", function(event, status) {
            $scope.results.loading = status;
        });

        $scope.setTab = function(statusTab) {
            $scope.checkedItens = [];
            $scope.selectedAll = false;
            $scope.currentPage = 1;
            $scope.tab = statusTab;
            $scope.estabelecimento.offset = 0;
            $scope.estabelecimento.limit = $scope.numPerPage;
            $scope.estabelecimento.status = statusTab;
            estabelecimentoService.set($scope.estabelecimento);
            estabelecimentoService.getList();
        };

        $scope.search = function() {
            estabelecimentoService.set($scope.estabelecimento);
            estabelecimentoService.getList();
        };

        $scope.setStatus = function(status) {
            estabelecimentoService.setIds($scope.checkedItens);
            estabelecimentoService.setStatus(status);
        };

        $scope.changePaginate = function() {
            $scope.estabelecimento.offset = ($scope.currentPage - 1) * $scope.numPerPage;
            $scope.estabelecimento.limit = $scope.numPerPage;
            estabelecimentoService.getList();
        };

        $scope.checkItem = function(item) {
            if (item.selected) {
                $scope.checkedItens.push(item.id);
            } else {
                var index = $scope.checkedItens.indexOf(item.id);
                $scope.checkedItens.splice(index, 1);
            }
        };

        $scope.checkAll = function() {
            $scope.selectedAll = !$scope.selectedAll;
            angular.forEach($scope.estabelecimentos.results, function(item) {
                item.selected = $scope.selectedAll;
                if (item.selected) {
                    $scope.checkedItens.push(item.id);
                } else {
                    var index = $scope.checkedItens.indexOf(item.id);
                    $scope.checkedItens.splice(index, 1);
                }
            });
        };
    }
]);