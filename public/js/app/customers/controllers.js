(function(){
    angular.module('customers.controllers',[])
        .controller('CustomerController',['$scope', '$routeParams','$location','crudService','socketService' ,'$filter','$route','$log',
            function($scope, $routeParams,$location,crudService,socket,$filter,$route,$log){
                $scope.customers = [];
                $scope.customer = {};
                $scope.generos = [{name:'Masculino'},{name:'Femenino'}];
                $scope.errors = null;
                $scope.success;
                $scope.customer.autogenerado = true;
                $scope.query = '';

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };

                $scope.pageChanged = function() {
                    if ($scope.query.length > 0) {
                        crudService.search('customers',$scope.query,$scope.currentPage).then(function (data){
                        $scope.customers = data.data;
                    });
                    }else{
                        crudService.paginate('customers',$scope.currentPage).then(function (data) {
                            $scope.customers = data.data;
                        });
                    }
                };


                var id = $routeParams.id;

                if(id)
                {
                    crudService.byId(id,'customers').then(function (data) {
                        $log.log(data);
                        if(data.fechaNac != null) {
                            if (data.fechaNac.length > 0) {
                                data.fechaNac = new Date(data.fechaNac);
                            }
                        }

                        $scope.customer = data;
                        $scope.customer.autogenerado = false;
                    });
                }else{
                    crudService.paginate('customers',1).then(function (data) {
                        $scope.customers = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });
                }

                socket.on('customer.update', function (data) {
                    $scope.customers=JSON.parse(data);
                });

                $scope.searchCustomer = function(){
                if ($scope.query.length > 0) {
                    crudService.search('customers',$scope.query,1).then(function (data){
                        $scope.customers = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudService.paginate('customers',1).then(function (data) {
                        $scope.customers = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };

                $scope.createCustomer = function(){

                    if ($scope.customerCreateForm.$valid) {
                        crudService.create($scope.customer, 'customers').then(function (data) {
                           
                            if (data['estado'] == true) {
                                $scope.success = data['nombres'];
                                alert('grabado correctamente');
                                $location.path('/customers');

                            } else {
                                $scope.errors = data;

                            }
                        });
                    }
                }
                $scope.createcashMonthlysss = function(){
                    alert("Hola");
                };

                $scope.editCustomer = function(row){
                    $location.path('/customers/edit/'+row.id);
                };

                $scope.updateCustomer = function(){
                    if ($scope.customerCreateForm.$valid) {
                        crudService.update($scope.customer,'customers').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/customers');
                            }else{
                                $scope.errors =data;
                            }
                        });
                    }
                };

                $scope.deleteCustomer = function(row){
                    $scope.customer = row;
                }

                $scope.cancelCustomer = function(){
                    $scope.customer = {};
                }

                $scope.destroyCustomer = function(){
                    crudService.destroy($scope.customer,'customers').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.customer = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }
            }]);
})();
