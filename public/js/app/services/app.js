(function(){
    var app = angular.module('services',[
        'ngRoute',
        'btford.socket-io',
        'ngSanitize',
        'services.controllers',
        'crud.services.services',
        'routes',
        'ui.bootstrap'
    ]);

    app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });
 
                event.preventDefault();
            }
        });
    };
});

    angular.module('services').controller('ModalInstanceCtrl', function ($scope,$log, $modalInstance,presentations,crudServiceServices) {
    $scope.presentations = presentations;
    //$log.log($scope.presentations+"Hola");
/*
  $scope.items = items;
  $scope.selected = {
    item: $scope.items[0]
  };
*/
  $scope.ok = function () {
    $modalInstance.close($scope.selected.item);
  };

  $scope.cancel = function () {
    $modalInstance.dismiss('cancel');
  };
   $scope.AsignarCompra = function(row){
        //$log.log(row);
        //alert("Equi : "+$scope.atributoSelected.equivalencia);
        //alert("stok : "+$scope.atributoSelected.Stock);
        if(row.equivalencia<(row.Stock-row.stockPedidos-row.stockSeparados) || row.equivalencia==(row.Stock-row.stockPedidos-row.stockSeparados)){
          crudServiceServices.setPres(row);

          crudServiceServices.AsignarCom();
          $scope.cancel();
        }else{
          alert("STOK Insuficioente");
        }
        //$log.log(row);
          //alert($scope.atributoSelected.NombreAtributos);
        //$scope.row1=row;
    };
});

    
})();