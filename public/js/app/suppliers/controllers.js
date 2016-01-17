(function(){
    angular.module('suppliers.controllers',[])
        .controller('SupplierController',['$scope', '$routeParams','$location','crudService','socketService' ,'$filter','$route','$log',
            function($scope, $routeParams,$location,crudService,socket,$filter,$route,$log){
                $scope.suppliers = [];
                $scope.supplier = {};
                $scope.errors = null;
                $scope.success;
                $scope.supplier.autogenerado = true;
                $scope.query = '';
                $scope.count={};
                $scope.counts=[];

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };

                $scope.pageChanged = function() {
                    if ($scope.query.length > 0) {
                        crudService.search('suppliers',$scope.query,$scope.currentPage).then(function (data){
                        $scope.suppliers = data.data;
                    });
                    }else{
                        crudService.paginate('suppliers',$scope.currentPage).then(function (data) {
                            $scope.suppliers = data.data;
                        });
                    }
                };


                var id = $routeParams.id;

                if(id)
                {
                   
                    crudService.byId(id,'suppliers').then(function (data) {
                        $log.log(data);
                        if(data.fechanac != null) {
                            if (data.fechanac.length > 0) {
                                data.fechanac = new Date(data.fechanac);
                            }
                        }

                        $scope.supplier = data;
                        crudService.Cuentas(data.id,"counts").then(function (data) {
                            $scope.counts=data;
                        });
                        $scope.supplier.autogenerado = false;
                    });
                }else{
                   crudService.deudasSupplier(1).then(function (data) {
                        $scope.suppliers = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });
                }

                socket.on('supplier.update', function (data) {
                    $scope.suppliers=JSON.parse(data);
                });

                $scope.searchSupplier = function(){
                if ($scope.query.length > 0) {
                    crudService.search('suppliers',$scope.query,1).then(function (data){
                        $scope.suppliers = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudService.paginate('suppliers',1).then(function (data) {
                        $scope.suppliers = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };
                $scope.addCuentas=function(){
                if($scope.count.NumCuenta!=null && $scope.count.banco!=null){
                     $scope.counts.push($scope.count);
                   $scope.count={}; 
                }else{
                    alert("Agrega una cuenta y selecciona un baco!!!!");
                }
                   
                }
                $scope.deleteCuenta=function(index){
                   if(confirm("esta seguro de querer eliminar esta cuenta??")==true){
                    $scope.counts.splice(index,1);
                  }
                }
                $scope.createSupplier = function(){
                    $scope.supplier.counts=$scope.counts;
                    if ($scope.supplierCreateForm.$valid) {
                        crudService.create($scope.supplier, 'suppliers').then(function (data) {
                          
                            if (data['estado'] == true) {
                                $scope.success = data['nombres'];
                                alert('grabado correctamente');
                                $location.path('/suppliers');

                            } else {
                                $scope.errors = data;

                            }
                        });
                    }
                }


                $scope.editSupplier = function(row){
                    $location.path('/suppliers/edit/'+row.id);
                };

                $scope.updateSupplier = function(){
                    $scope.supplier.counts=$scope.counts;
                    if ($scope.supplierCreateForm.$valid) {
                        crudService.update($scope.supplier,'suppliers').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/suppliers');
                            }else{
                                $scope.errors =data;
                            }
                        });
                    }
                };

                $scope.deleteSupplier = function(row){
                    $scope.supplier = row;
                }

                $scope.cancelSupplier = function(){
                    $scope.supplier = {};
                }

                $scope.destroySupplier = function(){
                    crudService.destroy($scope.supplier,'suppliers').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.supplier = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }
                
            }]);
})();
