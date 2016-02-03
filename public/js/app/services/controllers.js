(function(){
    angular.module('services.controllers',[])
        .controller('ServicesController',['$scope', '$routeParams','$location','crudServiceServices','socketService' ,'$filter','$route','$log','$window','$modal',
            function($scope, $routeParams,$location,crudServiceServices,socket,$filter,$route,$log,$window,$modal){
                $scope.services = [];
                $scope.service = {};
                $scope.errors = null;
                $scope.success;
                $scope.query = '';
                $scope.date = new Date();
                $scope.detServices={};
                $scope.detService={};
                $scope.bandera=false;

                $scope.warehouse={};
                $scope.store={};
                $scope.warehouse.id='1';
                $scope.store.id='1';
                $scope.base=true;
                $scope.customer={};
                $scope.customer.autogenerado=true;

                $scope.tipoServicio=0;

                $scope.compra={};


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
                    //alert("Hola");
                    crudServiceServices.byId(id,'services').then(function (data) {
                        $scope.service = data;
                        $scope.service.fechaServicio=new Date(data.fechaServicio);
                        $scope.numeracionMostrar(Number(data.numeroServicio)-1);
                        if ($scope.service.estado==5) {$scope.estBan=true;}else{$scope.estBan=false;};
                        if ($scope.service.estado>1 && $scope.service.estado<=5) {$scope.estBan1=true;}else{$scope.estBan1=false;};
                    });


                    crudServiceServices.select('stores','select').then(function (data) {                        
                        $scope.stores = data;

                    });
                    crudServiceServices.search('warehousesStore',$scope.store.id,1).then(function (data){
                        $scope.warehouses=data.data;
                    });

                    crudServiceServices.search('detServices',id,1).then(function (data){
                        $scope.detServices=data;
                    });
                }else{
                    //alert("Hola");
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
                    
                };$scope.variable="Imprimir";
                $scope.printDocument=function(id){
                    $scope.variable="Imprimiendo";
                    crudServiceServices.reporteServicio('reporteServicio',id).then(function (data) { 
                                                      $scope.variable="Imprimiendo";
                                                      if(data!=undefined){
                                                        $scope.variable="Imprimir";
                                                        $window.open(data);

                                                      }else{
                                                        alert("error de imprecion");
                                                      }
                                                       
                                            });
                }
                $scope.createService = function(){
                    $scope.service.estado = 1;
                    if ($scope.serviceCreateForm.$valid) {
                        crudServiceServices.create($scope.service, 'services').then(function (data) {
                          
                            if (data['estado'] == true) {
                                $scope.success = data['nombres'];
                                alert('grabado correctamente');
                                //$location.path('/services');
                                    crudServiceServices.reporteServicio('reporteServicio',data["id"]).then(function (data) { 

                                                      alert(data);
                                                       $window.open(data);
                                                       $location.path('/services');
                                            });
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
                    //$scope.service.estado = 2;
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

                $scope.destroyDetService = function(row){
                    $scope.detService = row;
                    crudServiceServices.destroy($scope.detService,'detServices').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.detService = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
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
                    //$log.log("entre");
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
                $scope.agregarTrabajo = function(){
                    $scope.compra.service_id=$scope.service.id;
                    $log.log($scope.compra);
                    crudServiceServices.create($scope.compra, 'detServices').then(function (data) {
                          
                            if (data['estado'] == true) {
                                alert('grabado correctamente');
                                $scope.compra=undefined;
                                crudServiceServices.search('detServices',id,1).then(function (data){
                                    $scope.detServices=data;
                                });
                            } else {
                                $scope.errors = data;
                            }
                        });
                }
                //-----------Busqueda Servicios-------------
                $scope.serviceSelected=undefined;
                $scope.getService = function(val) {
                  //return crudServiceServices.reportProWare('products',1,1,val).then(function(response){
                  return crudServiceServices.buscarServicio('listServices',$scope.store.id,val).then(function(response){
                    return response.map(function(item){
                        $log.log(item);
                      return item;
                    });
                  });
                };
                
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
                $scope.cargarservice = function(size){
                    //$log.log($scope.serviceSelected);
                    if ($scope.serviceSelected.NombreAtributos==undefined) {
                        alert("Ingrese servicio correctamente");
                    }else{
                        $scope.serviceSelected.cantidad=1;
                        if($scope.service.tipo==1){$scope.serviceSelected.descuento=100;}else{
                        $scope.serviceSelected.descuento=0;
                        }
                        $scope.serviceSelected.precioVenta=Number($scope.serviceSelected.precioProducto)-(Number($scope.serviceSelected.precioProducto)*$scope.serviceSelected.descuento/100);
                        $scope.serviceSelected.subTotal=$scope.serviceSelected.cantidad*Number($scope.serviceSelected.precioVenta);
                        
                        $scope.compra=$scope.serviceSelected;
                        //$log.log($scope.compra);
                        $scope.serviceSelected=undefined;
                    }
                }
                $scope.open = function (size) {                  
                    $scope.cargarAtri(size);                   
                };

                $scope.cargarAtri = function(size){
                    crudServiceServices.reportProWare('productsVariantes',$scope.store.id,$scope.warehouse.id,$scope.atributoSelected.vari).then(function(data){    
                        $scope.presentations = data;
                        var fecha = $scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                        if(fecha>=$scope.presentations[0].FechaInicioDescuento && fecha<=$scope.presentations[0].FechaFinDescuento){
                            if($scope.service.tipo==1){$scope.atributoSelected.descuento=100}else{
                                $scope.atributoSelected.descuento=Number($scope.presentations[0].DescuentoConFecha);
                            }
                            $scope.atributoSelected.cantidad=1;
                            $scope.atributoSelected.subTotal=$scope.atributoSelected.cantidad*Number($scope.atributoSelected.precioProducto);
                            $scope.atributoSelected.precioVenta=Number($scope.atributoSelected.precioProducto);
                        }else{
                            if($scope.service.tipo==1){$scope.atributoSelected.descuento=100}else{
                                $scope.atributoSelected.descuento=Number($scope.presentations[0].DescuentoSinFecha);
                            }
                            $scope.atributoSelected.cantidad=1;
                            $scope.atributoSelected.subTotal=$scope.atributoSelected.cantidad*Number($scope.atributoSelected.precioProducto);
                            $scope.atributoSelected.precioVenta=Number($scope.atributoSelected.precioProducto);
                        }


                        if($scope.base){
                            if ($scope.atributoSelected.NombreAtributos!=undefined) {
                                if (($scope.atributoSelected.Stock-$scope.atributoSelected.stockPedidos-$scope.atributoSelected.stockSeparados)>0) {       
                                    if($scope.service.tipo==1){$scope.atributoSelected.descuento=100}
                                    $log.log($scope.atributoSelected);
                    
                                    //$scope.compras.push($scope.atributoSelected);
                                    $scope.compra=$scope.atributoSelected;

                 
                                    $scope.bandera=true; 
                                    $scope.calcularmontos($scope.compra.length-1);

                                    //if ($scope.compra[$scope.compras.length-1].descuento>0) {
                                      //  $scope.sale.montoTotalSinDescuento=$scope.sale.montoTotalSinDescuento+Number($scope.compras[$scope.compras.length-1].precioProducto)-$scope.compras[$scope.compras.length-1].precioVenta;
                                    //};

                                    //$scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento+$scope.atributoSelected.subTotal;
                                    //$scope.recalcularCompra();
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


                //-----------------------------------------------
                $scope.aumentarCantidad= function(index){
                    if ($scope.compra.equivalencia!=undefined) {
                        //$log.log($scope.compras);
                        $scope.compra.cantidad=$scope.compra.cantidad+1;
                        if($scope.compra.cantidad*$scope.compra.equivalencia>($scope.compra.Stock-$scope.compra.stockPedidos-$scope.compra.stockSeparados)){
                            alert("STOK INSUFICIENTE");
                            $scope.compra.cantidad=$scope.compra.cantidad-1;
                        }else{
                            //$scope.compra.cantidad=$scope.compra.cantidad+1;
                            $scope.calcularmontos(index);    
                        }
                    }else{
                        //$log.log($scope.compras);
                        $scope.compra.cantidad=$scope.compra.cantidad+1;
                        $scope.calcularmontos(index); 
                        //$log.log($scope.sale);   
                    }
                                
                };
                $scope.disminuirCantidad= function(index){
                    $scope.compra.cantidad=$scope.compra.cantidad-1;
                    
                    $scope.calcularmontos(index);
                };
                $scope.aumentarPrecio= function(index){
                    $scope.compra.precioVenta=Number($scope.compra.precioVenta)+1;
                    $scope.calcularmontos(index);
                };
                $scope.disminuirPrecio= function(index){
                    $scope.compra.precioVenta=Number($scope.compra.precioVenta)-1;
                    $scope.calcularmontos(index);
                };
                $scope.aumentarDescuento= function(index){
                    $scope.compra.descuento=Number($scope.compra.descuento)+1;
                    $scope.bandera=true;
                    $scope.calcularmontos(index);
                };
                $scope.disminuirDescuento= function(index){
                    $scope.compra.descuento=Number($scope.compra.descuento)-1;
                    $scope.bandera=true;
                    $scope.calcularmontos(index);
                };
                $scope.keyUpDescuento= function(index){
                    $scope.bandera=true;
                    $scope.calcularmontos(index);
                };
                
                $scope.sacarRow=function(index){
                    //alert("Entre");
                    $scope.compra=undefined;
                }
                //-----------------------------------------
                $scope.calcularmontos=function(index){
                    if($scope.compra.oferta==undefined){
                          $scope.compra.oferta=0;
                    }
                    $scope.fechaActual=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                        
                     
                    if($scope.compra.cantidad>($scope.compra.Stock-$scope.compra.stockPedidos-$scope.compra.stockSeparados)){
                        $scope.compra.cantidad=1;
                        alert("Cantidad excede el STOCK");
                    }
                    if($scope.compra.cantidad<1){
                       $scope.compra.cantidad=1;
                       alert("La cantidad debe ser mayor 0"); 
                    }
                    //$scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento-$scope.compra.subTotal;

                    if($scope.bandera){
                        $scope.compra.precioVenta=((100-Number($scope.compra.descuento))*Number($scope.compra.precioProducto))/100;
                    }else{
                        $scope.compra.descuento=((Number($scope.compra.precioProducto)-Number($scope.compra.precioVenta))*100)/Number($scope.compra.precioProducto);
                    }
                    $scope.compra.subTotal=$scope.compra.cantidad*Number($scope.compra.precioVenta);

                    //$scope.sale.montoTotal=$scope.sale.montoTotal+$scope.compra.subTotal;
                    //$scope.recalcularCompra();
                    if($scope.compra.cantidad>=1){
                    crudServiceServices.confirmarVariante($scope.compra.vari,$scope.fechaActual).then(function(data){
                          if(data.sku!=undefined && data.cantidad<=$scope.compra.cantidad ){
                               if($scope.compra.oferta==0){
                                  if(confirm("Desea cargar oferta") == true){
                                      $scope.descuento10=data.descuento;
                                      $scope.varianteSkuSelected1=undefined;
                                      $scope.varianteSkuSelected=data.sku;
                                      $scope.oferta=true;
                                      $scope.base=true;
                                      $scope.compra.oferta=1;
                                      $scope.getvariantSKU(); 
                                      }else{
                                        $scope.bandera=false; 
                                      }
                                }else{
                                        $scope.oferta=false;
                                        $scope.bandera=false;
                                        $scope.descuento10=0;
                                }
                               }
                          
                     });
                      }
                };

                $scope.rutaMovimiento = function () {
                    //alert("Hola");
                    $scope.rutaSalesService='/sales/createS/'+$scope.service.id; 
                };
                //------------------------------------------
                $scope.dynamicPopover = {
                     content: 'Hello, World!',
                     templateUrl: 'myPopoverTemplate.html',
                     title: 'Quantity'
                     };

                     $scope.dynamicPopover1 = {
                     content: 'Hello, World!',
                     templateUrl: 'myPopoverTemplate1.html',
                     title: 'Precio',
                     title1: 'Descuento'
                     };

                     $scope.dynamicPopover5 = { 
                     content: 'Hello, World!',
                     templateUrl: 'myPopoverTemplate5.html',
                     title: 'Datos'
                     };

                     $scope.varianteSkuSelected;
                     $scope.varianteSkuSelected1=undefined;
                     $scope.getvariantSKU = function(size) {
                        $scope.fechaActual=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                        
                        crudServiceServices.reportProWare('productsSearchsku',$scope.store.id,$scope.warehouse.id,$scope.varianteSkuSelected).then(function(data){    
                            $scope.varianteSkuSelected1={};
                            $scope.varianteSkuSelected1=data;
                           
                              
                                   if (($scope.varianteSkuSelected1[0].Stock-$scope.varianteSkuSelected1[0].stockPedidos-$scope.varianteSkuSelected1[0].stockSeparados)>0) { 
                                         $scope.Jalar(size);
                                   }else{
                                       alert("STOCK INSUFICIENTE");
                                      $scope.varianteSkuSelected=undefined;
                                  } 
                           
                                                                                     
                        });
                    //}
                };

                $scope.Jalar = function(size) {
                    if ($scope.varianteSkuSelected1.length>0) {
                        crudServiceServices.reportProWare('productsVariantes',$scope.store.id,$scope.warehouse.id,$scope.varianteSkuSelected1[0].vari).then(function(data){    
                            $scope.presentations = data;

                            var fecha1 = $scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                            if($scope.oferta==true){
                                  if($scope.service.tipo==1){$scope.varianteSkuSelected1[0].descuento=100}else{
                                    $scope.varianteSkuSelected1[0].descuento=Number($scope.descuento10);
                                  }
                            }else{
                                  if(fecha1>=$scope.presentations[0].FechaInicioDescuento && fecha1<=$scope.presentations[0].FechaFinDescuento){
                                        if($scope.service.tipo==1){$scope.varianteSkuSelected1[0].descuento=100}else{
                                            $scope.varianteSkuSelected1[0].descuento=Number($scope.presentations[0].DescuentoConFecha);
                                        }
                                  }else{
                                        if($scope.service.tipo==1){$scope.varianteSkuSelected1[0].descuento=100}else{
                                            $scope.varianteSkuSelected1[0].descuento=Number($scope.presentations[0].DescuentoSinFecha);
                                        }
                                  }
                             }

                            if($scope.base){                
                                    $scope.varianteSkuSelected1[0].cantidad=1;
                                    $scope.varianteSkuSelected1[0].subTotal=$scope.varianteSkuSelected1[0].cantidad*Number($scope.varianteSkuSelected1[0].precioProducto);
                                    if($scope.service.tipo==1){$scope.varianteSkuSelected1[0].descuento=100}
                                    $scope.varianteSkuSelected1[0].precioVenta=Number($scope.varianteSkuSelected1[0].precioProducto);
                                   //$scope.compras.push($scope.varianteSkuSelected1[0]); 
                                   $scope.compra=$scope.varianteSkuSelected1[0];

                                   $scope.bandera=true; 
                                    $scope.calcularmontos($scope.compra.length-1);
                                   
                                    //if ($scope.compras[$scope.compras.length-1].descuento>0) {
                                      //  $scope.sale.montoTotalSinDescuento=$scope.sale.montoTotalSinDescuento+Number($scope.compras[$scope.compras.length-1].precioProducto)-$scope.compras[$scope.compras.length-1].precioVenta;
                                    //};
    
                                    //$scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento+$scope.varianteSkuSelected1[0].subTotal;
                                    //$scope.recalcularCompra();
                               
                                crudServiceServices.confirmarVariante($scope.varianteSkuSelected1[0].vari,$scope.fechaActual).then(function(data){
  
                                  $scope.varianteSkuSelected1=undefined;
                                  $scope.varianteSkuSelected='';
                                  $scope.oferta=false;
                          });
    
                            }else{                           
                                var modalInstance = $modal.open({      
                                    templateUrl: 'myModalContent.html',
                                    controller: 'ModalInstanceCtrl',
                                    size: size,
                                    resolve: {
                                        presentations: function () {
                                          return $scope.presentations;
                                        }                                    
                                    }
                                })
                                $scope.varianteSkuSelected1=undefined;
                                $scope.varianteSkuSelected="";
                            }
                        });
                    }else{
                        alert("Ingrese SKU Correctamente");
                        $scope.varianteSkuSelected1=undefined;
                        $scope.varianteSkuSelected="";
                    } 
                }

                crudServiceServices.AsignarCom = function (){
                    $scope.atributoSelected=crudServiceServices.getPres();

                        var fecha1 = $scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                            if(fecha1>=$scope.atributoSelected.FechaInicioDescuento && fecha1<=$scope.atributoSelected.FechaFinDescuento){
                                if($scope.service.tipo==1){$scope.atributoSelected.descuento=100}else{
                                    $scope.atributoSelected.descuento=Number($scope.atributoSelected.DescuentoConFecha);
                                }
                            }else{
                                if($scope.service.tipo==1){$scope.atributoSelected.descuento=100}else{
                                    $scope.atributoSelected.descuento=Number($scope.atributoSelected.DescuentoSinFecha);
                                }
                            }
                        if ($scope.atributoSelected.NombreAtributos!=undefined) {        
                                $scope.atributoSelected.cantidad=1;
                                $scope.atributoSelected.subTotal=$scope.atributoSelected.cantidad*Number($scope.atributoSelected.precioProducto);
                                $scope.atributoSelected.precioVenta=Number($scope.atributoSelected.precioProducto);
                                if($scope.service.tipo==1){$scope.atributoSelected.descuento=100}
                                //$scope.compras.push($scope.atributoSelected);  
                                $scope.compra=$scope.atributoSelected;

                                $scope.bandera=true; 
                                    $scope.calcularmontos($scope.compra.length-1);

                                    //if ($scope.compras[$scope.compras.length-1].descuento>0) {
                                      //  $scope.sale.montoTotalSinDescuento=$scope.sale.montoTotalSinDescuento+Number($scope.compras[$scope.compras.length-1].precioProducto)-$scope.compras[$scope.compras.length-1].precioVenta;
                                    //};


                                //$scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento+$scope.atributoSelected.subTotal;
                                //$scope.recalcularCompra();
                            }else{
                                alert("Seleccione Producto Correctamente");
                                $scope.atributoSelected=undefined;
                            }
                    
                    $scope.atributoSelected=undefined;
                }

            }]);
})();
