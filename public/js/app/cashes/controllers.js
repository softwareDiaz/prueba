(function(){
    angular.module('cashes.controllers',[])
        .controller('CashesController',['$scope', '$routeParams','$location','crudService','socketService' ,'$filter','$route','$log',
            function($scope, $routeParams,$location,crudService,socket,$filter,$route,$log){
                $scope.cashes = [];
                $scope.cash={};
                $scope.errors = null;
                $scope.success;
                $scope.query = '';
                $scope.cashHeaders = {};
                $scope.cashHeader={};
                $scope.detCashes={};
                $scope.buscar;
                $scope.date = new Date(); 
                $scope.stores={};
                $scope.bandera=true;
                $scope.banderaAbrirCaja=false;
                $scope.banderaestado=false;
                $scope.mostrarCajas = function () {

                    crudService.search('searchHeaders',$scope.stores.id,1).then(function (data){
                        $scope.cashHeaders=data;
                    });
                    if ($scope.stores.id==undefined) {
                    crudService.search('cashes1',0,1).then(function (data){
                        $scope.cashes = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;
                        $scope.verCaja();
                        });
                        $scope.banderaAbrirCaja=false;
                        $scope.cash.cashHeader_id=undefined;
                    }
                    
                };
                $scope.cargarCajasDiarias = function () {
                    //if (true) {};
                    //alert($scope.cash.cashHeader_id);
                    if ($scope.cash.cashHeader_id!=undefined) {
                   crudService.search('cashes1',$scope.cash.cashHeader_id,1).then(function (data){
                        $scope.cashes = data.data;
                        $scope.maxSize = 5; 
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;
                        $scope.verCaja();
                    });
                    $scope.banderaAbrirCaja=true;
                    }else{
                        crudService.search('cashes1',$scope.cash.cashHeader_id,1).then(function (data){
                        $scope.cashes = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;
                        $scope.verCaja();
                        });
                        $scope.banderaAbrirCaja=false;   
                    }
                     
                }

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };
                $scope.rutaMovimiento = function () {
                    $scope.rutaDetCash='/detCashes/create/'+$scope.cash.id; 
                };
                $scope.calculardescuadre = function () {
                    $scope.cash.descuadre=Number($scope.cash.montoReal)-Number($scope.cash.montoBruto);
                };
                    
                $scope.verCaja = function () {
                    $scope.cash1={};
                    if ($scope.cashes.length==0) {
                        alert("Caja Cerrada");
                        $scope.bandera=true;
                    }else{
                        var canpag=Math.ceil($scope.totalItems/15);

                        crudService.search('cashes1',$scope.cash.cashHeader_id,canpag).then(function (data){
                            $scope.cashesedit = data.data;
                            $scope.cash1=$scope.cashesedit[0];
                            if ($scope.cash1.estado=='0') {
                                //alert("Caja Cerrada");
                                $scope.bandera=true;

                            }else{
                                //alert("Caja Abierta");
                                $scope.ruta='/cashes/edit/'+$scope.cash1.id;
                                $scope.bandera=false;
                            }
                        });
                    }

                };
                $scope.cerrarCaja = function () {
                    //alert($scope.cash.montoReal);
                    if($scope.cash.montoReal<'0.00' || $scope.cash.montoReal==undefined){
                        
                        alert("Ingrese Monto Real");
                    }else{
                        $scope.calculardescuadre();
                        //7alert($scope.date.getDate());
                        $scope.cash.fechaFin=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate()+' '+$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                            $scope.cash.estado='0';
                            //alert($scope.cash.fechaFin);
                            $log.log($scope.cash);
                        if ($scope.cashCreateForm.$valid) {  
                            
                        crudService.update($scope.cash,'cashes').then(function(data)
                        {
                            if(data['estado'] == true){
                                
                                //$scope.success = data['nombres'];
                                alert('Caja Cerrada');
                                $location.path('/cashes');
                            }else{
                                $scope.errors =data;
                            }
                        });
                        }
                    }
                };
                

                $scope.pageChanged = function() {
                    /*
                    if ($scope.query.length > 0) {
                        crudService.search('cashes',$scope.query,$scope.currentPage).then(function (data){
                        $scope.cashes = data.data;
                    });
                    }else{
                        crudService.paginate('cashes',$scope.currentPage).then(function (data) {
                            $scope.cashes = data.data;
                        });
                    }*/

                    if ($scope.cash.cashHeader_id!=null) {
                   crudService.search('cashes',$scope.cash.cashHeader_id,$scope.currentPage).then(function (data){
                        $scope.cashes = data.data;
                        //$scope.maxSize = 5;
                        //$scope.totalItems = data.total;
                        //$scope.currentPage = data.current_page;
                        //$scope.itemsperPage = 15;
                        //$scope.verCaja();
                    });
                    //$scope.banderaAbrirCaja=true;
                    }else{
                        crudService.paginate('cashes',$scope.currentPage).then(function (data) {
                            $scope.cashes = data.data;
                            $log.log("------------------");
                            $log.log($scope.cashes);
                            $log.log("------------------");
                        });  
                    }
                };
                //---------------------------------------------
                $scope.pageChanged1 = function() {
                    if ($scope.cash.id != 0) {
                        //alert("entre : "+$scope.cash.id)
                        crudService.search('detCashes',$scope.buscar,$scope.currentPage1).then(function (data){
                        $scope.detCashes = data.data;
                    });
                    }else{
                        crudService.paginate('detCashes',$scope.currentPage).then(function (data) {
                            $scope.detCashes = data.data;
                        });
                    }
                    //$log.log($scope.totalItems1);
                };
                //--------------------------------------------------


                
                var id = $routeParams.id;

                if(id)
                {
                    crudService.byId(id,'cashes').then(function (data) {
                        $scope.cash = data;
                       // $scope.buscar=$scope.cash.id;
                        //$log.log($scope.cash);

                       /* crudService.search('detCashes',$scope.cash.id,1).then(function (data){
                           $scope.detCashes = data.data;
                           $scope.maxSize1 = 5;
                            $scope.totalItems1 = data.total;
                            $scope.currentPage1 = data.current_page;
                            $scope.itemsperPage1 = 15;
                            $log.log($scope.detCashes);

                            //$log.log($scope.detCashes);
                        });*/
                        
                    //});
                    //crudService.select('cashHeaders','select').then(function (data) {                        
                    //    $scope.cashHeaders = data;
                    });
                    //-------------------------------------------------------

                        crudService.search('detCashes',id,1).then(function (data) {
                            $scope.detCashes = data.data;
                            $scope.maxSize1 = 5;
                            $scope.totalItems1 = data.total;
                            $scope.currentPage1 = data.current_page;
                            $scope.itemsperPage1 = 15;
                            //$log.log(data);
                        });

                    //-------------------------------------------------------

                    
                }else{
                    crudService.paginate('cashes',1).then(function (data) {
                        $scope.cashes = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;
                        //$scope.numPages1 =data.last_page;
                    });
                    //crudService.select('cashHeaders','select').then(function (data) {                        
                      //  $scope.cashHeaders = data;
                    //});
                    crudService.select('stores','select').then(function (data) {                        
                        $scope.stores = data;
                        $log.log($scope.stores);
                    });

                }

                socket.on('cashes.update', function (data) {
                    $scope.cashes=JSON.parse(data);
                });
                


                $scope.searchcash = function(){
                if ($scope.query.length > 0) {
                    
                    crudService.search('cashes',$scope.query,1).then(function (data){
                        $scope.cashes = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudService.paginate('cashes',1).then(function (data) {
                        $scope.cashes = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };

                $scope.createcash = function(){
                    //$log.log($scope.cash.montoInicial);
                    //if ($scope.cashCreateForm.$valid) {
                    if ($scope.cash.montoInicial==undefined) {
                        alert("Ingrese monto Inicio");
                    }else{
                       $scope.cash.fechaInicio=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate()+' '+$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                        $scope.cash.estado='1'; 
                        $scope.cash.montoBruto=$scope.cash.montoInicial;
                        crudService.create($scope.cash, 'cashes').then(function (data) {
                           
                            if (data['estado'] == true) {
                                $scope.success = data['nombres'];
                                alert('grabado correctamente');
                                //$location.path('/cashes');
                                $scope.cargarCajasDiarias();

                            } else {
                                $scope.errors = data;

                            }
                        });
                    }
                        
                    //}
                }

                $scope.editcash = function(row){
                    
                    crudService.Comprueba_caj_for_user1(row.id).then(function (data){
                        if(data.id!=undefined){
                             if (row.estado=='0') {
                                 //alert("La caja esta Cerrada");
                                 $scope.banderaestado=false;
                             }
                             else{
                                $scope.banderaestado=true; 
                             }
                             $location.path('/cashes/edit/'+row.id); 
                        }else{
                            alert("usted no tiene permisos para editar esta caja");
                        }
                    });
                     //$scope.cash.montoInicial=parseInt($scope.cash.montoInicial);
                };

                $scope.updatecash = function(){
                   if ($scope.cashCreateForm.$valid) {
                        crudService.update($scope.cash,'cashes').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/cashes');
                            }else{
                                $scope.errors =data;
                            }
                        });
                    }
                };

                $scope.deletecash = function(row){
                    $scope.cash = row;
                    alert(row.nombre);
                }

                $scope.cancelcash = function(){
                    $scope.cash = {};
                }

                $scope.destroycash = function(){
                    crudService.destroy($scope.cash,'cashes').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.cash = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }
            }]);
})();
