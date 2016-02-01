(function(){
    angular.module('listServices.controllers',[])
        .controller('ListServiceController',['$scope', '$routeParams','$location','crudService','socketService' ,'$filter','$route','$log',
            function($scope, $routeParams,$location,crudService,socket,$filter,$route,$log){
                $scope.listServices = [];
                $scope.listService = {};
                $scope.errors = null;
                $scope.success;
                $scope.query = '';
                 $scope.listService.estado=true;
                 $scope.listService.tipo='Normal';
                 $scope.store={};
                $scope.store.id='1';

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };

                $scope.pageChanged = function() {
                    if ($scope.query.length > 0) {
                        crudService.search('listServices',$scope.query,$scope.currentPage).then(function (data){
                        $scope.listServices = data.data;
                    });
                    }else{
                        crudService.paginate('listServices',$scope.currentPage).then(function (data) {
                            $scope.listServices = data.data;
                        });
                    }
                };


                var id = $routeParams.id;

                if(id)
                {
                    crudService.byId(id,'listServices').then(function (data) {
                        $scope.listService = data;
                        $scope.listService.costoAprox=Number(data.costoAprox);
                        if(data.estado==1){
                            $scope.listService.estado=true;
                        }
                    });
                }else{
                    crudService.paginate('listServices',1).then(function (data) {
                        $scope.listServices = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });
                    crudService.select('stores','select').then(function (data) {                        
                        $scope.stores = data;
                    });
                }

                socket.on('brand.update', function (data) {
                    $scope.listServices=JSON.parse(data);
                });

                $scope.searchListService = function(){
                if ($scope.query.length > 0) {
                    crudService.search('listServices',$scope.query,1).then(function (data){
                        $scope.listServices = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudService.paginate('listServices',1).then(function (data) {
                        $scope.listServices = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };
                 $scope.validanomMarca=function(texto){
                
                   if(texto!=undefined){
                        crudService.validar('listServices',texto).then(function (data){
                        $scope.brand = data;
                        if($scope.brand!=null){
                           alert("Usted no puede crear dos Marcas con el mismo nombre");
                           $scope.brand.nombre=''; 
                           $scope.brand.shortname=''; 
                        }
                    });
                    }
               }

                $scope.createlistService = function(){
                    //$scope.atribut.estado = 1;
                    $scope.listService.store_id=$scope.store.id
                    if ($scope.listServiceCreateForm.$valid) {
                        crudService.create($scope.listService, 'listServices').then(function (data) {
                          
                            if (data['estado'] == true) {
                                alert('grabado correctamente');
                                $location.path('/listServices');

                            } else {
                                $scope.errors = data;

                            }
                        });
                    }
                }


                $scope.editlistService = function(row){
                    $location.path('/listServices/edit/'+row.id);
                };
                 $scope.DeletelistService = function(row){
                    
                    //crudService.byId(id,'listServices').then(function (data) {
                      //  $scope.listService = data;
                        if(row.estado==1){
                            row.estado=0;
                        }else{
                             row.estado=1;
                        }
                   
                        crudService.update(row,'listServices').then(function(data)
                        {
                            if(data['estado'] == true){
                                alert('editado correctamente');
                                $location.path('/listServices');
                            }else{
                                $scope.errors =data;
                            }
                        });
                     //});
                };

                $scope.updatelistService = function(){

                    if ($scope.listServiceCreateForm.$valid) {
                        crudService.update($scope.listService,'listServices').then(function(data)
                        {
                            if(data['estado'] == true){
                                alert('editado correctamente');
                                $location.path('/listServices');
                            }else{
                                $scope.errors =data;
                            }
                        });
                    }
                };

                $scope.deleteBrand = function(row){
                    $scope.brand = row;
                }

                $scope.cancelBrand = function(){
                    $scope.brand = {};
                }

                $scope.destroyBrand = function(){
                    crudService.destroy($scope.brand,'listServices').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.brand = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }
            }]);
})();