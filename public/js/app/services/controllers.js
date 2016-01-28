(function(){
    angular.module('services.controllers',[])
        .controller('ServicesController',['$scope', '$routeParams','$location','crudServiceServices','socketService' ,'$filter','$route','$log',
            function($scope, $routeParams,$location,crudServiceServices,socket,$filter,$route,$log){
                $scope.services = [];
                $scope.service = {};
                $scope.errors = null;
                $scope.success;
                $scope.query = '';

                $scope.warehouse={};
                $scope.store={};
                $scope.warehouse.id='1';
                $scope.store.id='1';

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };

                $scope.pageChanged = function() {
                    if ($scope.query.length > 0) {
                        crudServiceServices.search('services',$scope.query,$scope.currentPage).then(function (data){
                        $scope.services = data.data;
                    });
                    }else{
                        crudServiceServices.paginate('services',$scope.currentPage).then(function (data) {
                            $scope.services = data.data;
                        });
                    }
                };


                var id = $routeParams.id;

                if(id)
                {
                    crudServiceServices.byId(id,'services').then(function (data) {
                        $scope.service = data;
                        $scope.service.fechaServicio=new Date(data.fechaServicio);
                        $scope.numeracionMostrar(Number(data.numeroServicio)-1);
                    });

                    crudServiceServices.select('stores','select').then(function (data) {                        
                        $scope.stores = data;

                    });
                    crudServiceServices.search('warehousesStore',$scope.store.id,1).then(function (data){
                        $scope.warehouses=data.data;
                    });
                }else{
                    crudServiceServices.paginate('services',1).then(function (data) {
                        $scope.services = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });

                    crudServiceServices.numeracion("services").then(function (data){
                                          //$scope.numActual="0000"+(Number(data.numFactura)+1);
                                        $scope.numeracionMostrar(data.numeroServicio);
                                        //$log.log(data.numeroServicio);
                               });
                }

                socket.on('service.update', function (data) {
                    $scope.services=JSON.parse(data);
                });

                $scope.searchService = function(){
                if ($scope.query.length > 0) {
                    crudServiceServices.search('services',$scope.query,1).then(function (data){
                        $scope.services = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudServiceServices.paginate('services',1).then(function (data) {
                        $scope.services = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };

                $scope.createService = function(){
                    $scope.service.estado = 1;
                    if ($scope.serviceCreateForm.$valid) {
                        crudServiceServices.create($scope.service, 'services').then(function (data) {
                          
                            if (data['estado'] == true) {
                                $scope.success = data['nombres'];
                                alert('grabado correctamente');
                                $location.path('/services');

                            } else {
                                $scope.errors = data;

                            }
                        });
                    }
                }


                $scope.editService = function(row){
                    $location.path('/services/edit/'+row.id);
                };
                $scope.editServiceDiagnostico = function(row){
                    $location.path('/services/editD/'+row.id);
                };
                
                $scope.updateService = function(){
                    if ($scope.serviceEditForm.$valid) {
                        crudServiceServices.update($scope.service,'services').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/services');
                            }else{
                                $scope.errors =data;
                            }
                        });
                    }
                };

                $scope.update1Service = function(){
                    $scope.service.estado = 2;
                    if ($scope.serviceEditForm.$valid) {
                        crudServiceServices.update($scope.service,'services').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/services');
                            }else{
                                $scope.errors =data;
                            }
                        });
                    }
                };

                $scope.deleteService = function(row){
                    $scope.service = row;
                }

                $scope.cancelService = function(){
                    $scope.service = {};
                }

                $scope.destroyService = function(){
                    crudServiceServices.destroy($scope.service,'services').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.service = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }

                //------Servicios buscar cliente--------
                $scope.customerSelected=undefined;
                $scope.getCustomer = function(val) {
                    //$log.log(val);
                  return crudServiceServices.search('customersVenta',val,1).then(function(response){
                    return response.data.map(function(item){
                      return item;
                    });
                  });
                };
                $scope.selecionarCliente = function() {
                    //$log.log($scope.customersSelected.busqueda);
                    $log.log("entre");
                    if ($scope.customerSelected!=undefined) {
                        $scope.service.customer_id=$scope.customerSelected.id;
                        $scope.service.cliente=$scope.customerSelected.nombres+" "+$scope.customerSelected.apellidos;
                        $scope.service.empresa=$scope.customerSelected.empresa;
                        $scope.service.ruc=$scope.customerSelected.ruc;
                        $scope.service.direcion=$scope.customerSelected.direccFiscal;
                        $scope.service.telefono=$scope.customerSelected.fijo+" -- "+$scope.customerSelected.movil;
                        $scope.customerSelected=undefined;
                    };
                }

                $scope.numeracionMostrar=function(num){
                                            if(num==undefined){num=0}
                                            if(Number(num)<9){
                                                $scope.numActual="000000"+(Number(num)+1);
                                            }else{
                                            if(Number(num)<100){
                                               $scope.numActual="00000"+(Number(num)+1);
                                            }else{
                                            if(Number(num)<1000){
                                               $scope.numActual="0000"+(Number(num)+1);
                                            }else{
                                            if(Number(num)<10000){
                                               $scope.numActual="000"+(Number(num)+1);
                                            }else{
                                            if(Number(num)<100000){
                                               $scope.numActual="00"+(Number(num)+1);
                                            }else{
                                            if(Number(num)<1000000){
                                               $scope.numActual="0"+(Number(num)+1);
                                            }else{
                                            if(Number(num)>=1000000){
                                               $scope.numActual=""+(Number(num)+1);
                                            }}}}}}}
                        $scope.service.numeroServicio=$scope.numActual;
                }

                $scope.createCustomer = function(){

                    //if ($scope.customerCreateForm.$valid) {
                        crudServiceServices.create($scope.customer, 'customers').then(function (data) {
                           
                            if (data['estado'] == true) {
                                $scope.exitCustumer=true;
                                $scope.success = data['nombres'];
                                $('#miventana2').modal('hide');
                                alert('grabado correctamente');
                                
                                //$location.path('/customers');

                            } else {
                                $scope.errors = data.responseJSON;

                            }
                        });
                    //}
                    $scope.customer={};
                }

                //-----------Busqueda productos-------------
                $scope.atributoSelected=undefined;
                $scope.getAtributos = function(val) {
                  //return crudServiceServices.reportProWare('products',1,1,val).then(function(response){
                  return crudServiceServices.reportProWare('products',$scope.store.id,$scope.warehouse.id,val).then(function(response){
                    return response.map(function(item){
                        $log.log(item);
                      return item;
                    });
                  });
                };

                $scope.open = function (size) {
                    //$log.log($scope.atributoSelected+" open");                    
                    $scope.cargarAtri(size);                   
                };

                $scope.cargarAtri = function(size){
                    crudServiceOrders.reportProWare('productsVariantes',$scope.store.id,$scope.warehouse.id,$scope.atributoSelected.vari).then(function(data){    
                        $scope.presentations = data;
                        var fecha = $scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                        if(fecha>=$scope.presentations[0].FechaInicioDescuento && fecha<=$scope.presentations[0].FechaFinDescuento){
                            $scope.atributoSelected.descuento=Number($scope.presentations[0].DescuentoConFecha);
                            $scope.atributoSelected.cantidad=1;
                            $scope.atributoSelected.subTotal=$scope.atributoSelected.cantidad*Number($scope.atributoSelected.precioProducto);
                            $scope.atributoSelected.precioVenta=Number($scope.atributoSelected.precioProducto);
                        }else{
                            $scope.atributoSelected.descuento=Number($scope.presentations[0].DescuentoSinFecha);
                            $scope.atributoSelected.cantidad=1;
                            $scope.atributoSelected.subTotal=$scope.atributoSelected.cantidad*Number($scope.atributoSelected.precioProducto);
                            $scope.atributoSelected.precioVenta=Number($scope.atributoSelected.precioProducto);
                        }


                        if($scope.base){
                            if ($scope.atributoSelected.NombreAtributos!=undefined) {
                                if (($scope.atributoSelected.Stock-$scope.atributoSelected.stockPedidos-$scope.atributoSelected.stockSeparados)>0) {       
                                    
                                    $log.log($scope.atributoSelected);
                    
                                    $scope.compras.push($scope.atributoSelected);


                 
                                    $scope.bandera=true; 
                                    $scope.calcularmontos($scope.compras.length-1);

                                    if ($scope.compras[$scope.compras.length-1].descuento>0) {
                                        $scope.sale.montoTotalSinDescuento=$scope.sale.montoTotalSinDescuento+Number($scope.compras[$scope.compras.length-1].precioProducto)-$scope.compras[$scope.compras.length-1].precioVenta;
                                    };

                                    $scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento+$scope.atributoSelected.subTotal;
                                    $scope.recalcularCompra();
                                }else{
                                    alert("STOK INSUFICIENTE");
                                }   
                            }else{
                                alert("Seleccione Producto Correctamente");
                                $scope.atributoSelected=undefined;
                            }
                            $scope.atributoSelected=undefined;

                        }else{                           
                            var modalInstance = $modal.open({      
                                //animation: $scope.animationsEnabled,
                                templateUrl: 'myModalContent.html',
                                controller: 'ModalInstanceCtrl',
                                size: size,
                                resolve: {
                                    presentations: function () {
                                      return $scope.presentations;
                                    }                                    
                                }
                            })
                        }
                    });
                    
                }

            }]);
})();
