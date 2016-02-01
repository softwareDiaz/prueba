(function(){
    angular.module('sales.controllers',[])
        .controller('SaleController',['$scope', '$routeParams','$location','crudServiceOrders','socketService' ,'$filter','$route','$log','$window','$modal',
            function($scope, $routeParams,$location,crudServiceOrders,socket,$filter,$route,$log, $window,$modal){
                
                $scope.errors = null;
                $scope.success;
                $scope.query = ''; 
                $scope.promocion={};
                $scope.promociones=[];
                $scope.banService=false;

                $scope.inicializar = function (){
                    $scope.orders = [];
                    $scope.sale={};
                    $scope.atributos={};
                    $scope.compras=[];
                    $scope.compra={};
                    $scope.sale.montoBruto=0;
                    $scope.sale.descuento=0;
                    $scope.sale.montoTotal=0;
                    $scope.sale.notas="";
                    $scope.sale.montoTotalSinDescuento=0;
                    $scope.sale.igv=0;
                    $scope.bandera=false;
                    $scope.banderaNotas=true;
                    $scope.banderaPagosCash=true;
                    $scope.banderadeleteFavorito=true;
                    $scope.banderaPagosTarjeta=true;
                    $scope.acuenta=false;
                    $scope.customer={};
                    $scope.date = new Date();
                    $scope.base=true;
                    $scope.skuestado=true;
                    $scope.presentations=[];
                    $scope.pago={};
                    $scope.pago.tarjeta=0;
                    $scope.pago.cash=0;
                    $scope.radioModel=undefined;
                    $scope.saledetPayments=[];
                    $scope.detPayments={};
                    $scope.saledetPayment={};
                    $scope.salePayment={};
                    $scope.payment={};
                    $scope.atributoSelected=undefined;
                    $scope.customersSelected=undefined;
                    $scope.employeeSelected=undefined;
                    $scope.sale.customer_id=undefined;
                    $scope.sale.employee_id=undefined;
                    $scope.sale.vuelto=0;
                    $scope.exitCustumer=false;                  
                    $scope.cashes={};
                    $scope.cashfinal={};
                    $scope.banderaMostrarEntrega=false;
                    $scope.banderaModificar=false;
                    $scope.banderaEstadoService=true;

                }
                
                $scope.inicializar();
                $scope.cash1={};
                $scope.warehouse={};
                $scope.store={};
                $scope.cash1.cashHeader_id='1';
                $scope.warehouse.id='1';
                $scope.store.id='1';

                $scope.estadoMostrarEntrega = function () {
                    if ($scope.order1.estado!=0) {$scope.banderaMostrarEntrega=true;}else{
                        $scope.banderaMostrarEntrega=false;
                    }
                };
                $scope.oPres;
                $scope.limpiartipoTarjeta= function () {
                    $scope.radioModel=undefined;      
                }
                $scope.mostrarAlmacenCaja = function () {
                    crudServiceOrders.search('searchHeaders',$scope.store.id,1).then(function (data){
                        $scope.cashHeaders=data;
                    });
                    crudServiceOrders.search('warehousesStore',$scope.store.id,1).then(function (data){
                        $scope.warehouses=data.data;
                    });
                    crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                        var canCashes=data.total;
                        var pagActual=Math.ceil(canCashes/15);
                        crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){
                            $scope.cashes = data.data;
                            $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];
                        });
                    });    
                }
                var id = $routeParams.id;

                if(id)
                {   
                    crudServiceOrders.search('detServices',id,1).then(function (data){
                        $scope.deleteCompras=data;
                    });
                    crudServiceOrders.search('detServices',id,1).then(function (data){
                        $scope.compras=data;
                        for (var i = $scope.compras.length - 1; i >= 0; i--) {
                            if ($scope.compras[i].service_id != undefined) {
                                $scope.compras[i].cantidad=Number($scope.compras[i].cantidad);
                                $scope.compras[i].descuento=Number($scope.compras[i].descuento);
                                $scope.compras[i].precioVenta=Number($scope.compras[i].precioVenta);
                                $scope.compras[i].subTotal=Number($scope.compras[i].subTotal);

                                $scope.sale.montoTotal=$scope.sale.montoTotal+$scope.compras[i].subTotal;
                                
                            };
                        };
                        $scope.recalcularCompra();

                        crudServiceOrders.byId(id,'services').then(function (data) {
                            $scope.findService=data;
                            if (data.estado==5) {
                                $scope.banderaEstadoService=false;
                            };
                            crudServiceOrders.byId(data.customer_id,'customers').then(function (data) {
                                $scope.sale.customer_id=data.id;
                                $scope.sale.cliente=data.nombres+"-"+data.apellidos+"-"+data.empresa;
                            });
                        });

                        
                    });
                    crudServiceOrders.byId(id,'sales').then(function (data) {
                        $scope.order1 = data;
                        $scope.estadoMostrarEntrega();
                        if ($scope.order1.estado==0) {$scope.cancelPedido=false}else if($scope.order1.estado==3){$scope.cancelPedido=true};

                        crudServiceOrders.search('DetSales',$scope.order1.id,1).then(function (data){
                            $scope.detOrders = data.data;
                        });
                        crudServiceOrders.search('salePayment',$scope.order1.id,1).then(function (data){
                            $scope.payment = data.data;

                            if($scope.payment.length!=0){
                            $scope.calcularPorcentaje();

                            
                            crudServiceOrders.search('SaleDetPayment',$scope.payment[0].idPAY,1).then(function (data){
                                $scope.detPayments = data.data;
                                $scope.maxSize = 5;
                                    $scope.totalItems = data.total;
                                    $scope.currentPage = data.current_page;
                                    $scope.itemsperPage = 5;
                            });
                            }
                        });
                    });
                    crudServiceOrders.select('saleMethodPayments','select').then(function (data) {                        
                        $scope.saleMethodPayments = data;

                    });
                    crudServiceOrders.select('stores','select').then(function (data) {                        
                        $scope.stores = data;
                    });
                    crudServiceOrders.search('warehousesStore',$scope.store.id,1).then(function (data){
                        $scope.warehouses=data.data;
                    });
                    crudServiceOrders.search('searchHeaders',$scope.store.id,1).then(function (data){
                        $scope.cashHeaders=data;
                    });

                    crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                        var canCashes=data.total;
                        var pagActual=Math.ceil(canCashes/15);
                        crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){
                            $scope.cashes = data.data;
                            $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];
                            crudServiceOrders.search('detCashesSale',$scope.cashfinal.id,1).then(function (data){
                            $scope.detCashes = data.data;
                            $scope.maxSize1 = 5;
                                $scope.totalItems1 = data.total;
                                $scope.currentPage1 = data.current_page;
                                $scope.itemsperPage1 = 15;

                            });
                        });
                    });

                }else{
                    crudServiceOrders.paginate('sales',1).then(function (data) {
                        $scope.orders = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });

                    crudServiceOrders.select('stores','select').then(function (data) {                        
                        $scope.stores = data;

                    });
                    crudServiceOrders.search('warehousesStore',$scope.store.id,1).then(function (data){
                        $scope.warehouses=data.data;
                    });
                    crudServiceOrders.reportProWare('productsFavoritos',$scope.store.id,$scope.warehouse.id,'1').then(function(data){    
                        $scope.favoritos=data;
                    });

                    crudServiceOrders.search('searchHeaders',$scope.store.id,1).then(function (data){
                        $scope.cashHeaders=data;
                    });

                    crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                        var canCashes=data.total;
                        var pagActual=Math.ceil(canCashes/15);
                        crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){
                            $scope.cashes = data.data;
                            $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];
                            crudServiceOrders.search('detCashesSale',$scope.cashfinal.id,1).then(function (data){
                            $scope.detCashes = data.data;
                            $scope.maxSize1 = 5;
                                $scope.totalItems1 = data.total;
                                $scope.currentPage1 = data.current_page;
                                $scope.itemsperPage1 = 15;

                            });
                        });
                    });
                    //$scope.detCashes={};
                    
                }

                $scope.destroyDetService = function(row){
                    $scope.detService = row;
                    crudServiceOrders.destroy($scope.detService,'detServices').then(function(data)
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
                $scope.createmovCaja = function(tipo){
                    $scope.detCash={};
                    $scope.mostrarAlmacenCaja();

                    $scope.detCash.cash_id=$scope.cashfinal.id; 
                    $scope.detCash.fecha=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                    $scope.detCash.hora=$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                    $scope.detCash.montoCaja=$scope.cashfinal.montoBruto;
                    
                    $scope.detCash.montoMovimientoTarjeta=Number($scope.pago.tarjeta);
                    $scope.detCash.montoMovimientoEfectivo=Number($scope.pago.cash);
                    $scope.detCash.montoFinal=Number($scope.detCash.montoCaja)+$scope.detCash.montoMovimientoTarjeta+$scope.detCash.montoMovimientoEfectivo;
                    $scope.detCash.estado='1'; 
                    //alert(tipo);
                    if(tipo=='credito'){
                        $scope.detCash.cashMotive_id='14';    
                    }else if(tipo=='contado'){
                        $scope.detCash.cashMotive_id='1';   
                    }
                    

                    $scope.cashfinal.ingresos=Number($scope.cashfinal.ingresos)+Number($scope.detCash.montoMovimientoTarjeta)+Number($scope.detCash.montoMovimientoEfectivo); 
                    $scope.cashfinal.montoBruto=$scope.detCash.montoFinal;
                    //////////////////////////////////////////////

                    $scope.sale.fechaPedido=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate()+' '+$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                    $scope.sale.detOrders=$scope.compras;
                    $scope.sale.movimiento=$scope.detCash; 
                    $scope.sale.caja=$scope.cashfinal;
                }
                 $scope.detVoices=[];
                 $scope.headVoice={};
                $scope. createorder = function(tipo){
                    //alert("Hola");
                    if (id!=undefined) {
                        $scope.banService=true;
                        $log.log("Bandera");
                    };

                    crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                        var canCashes=data.total;
                        var pagActual=Math.ceil(canCashes/15);
                        crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){

                            $scope.cashes = data.data;
                            $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];
                            $scope.createmovCaja(tipo);

                            for (var i = 0; i < $scope.sale.saledetPayments.length; i++) {
                                $scope.sale.saledetPayments[i].numCaja=$scope.detCash.cash_id;
                            };


                                            if ($scope.banService==true) {
                                                for (var i = $scope.deleteCompras.length - 1; i >= 0; i--) {
                                                    $scope.destroyDetService($scope.deleteCompras[i]);
                                                };

                                            for (var i = $scope.sale.detOrders.length - 1; i >= 0; i--) {
                                                $scope.sale.detOrders[i].service_id=id;
                                                crudServiceOrders.create($scope.sale.detOrders[i], 'detServices').then(function (data) {
                                                    if (data['estado'] == true) {
                                                    } else {
                                                        $scope.errors = data;
                                                    }
                                                });
                                            };
                                        };
                                        $scope.sale.banseraServicio=$scope.banService;
                                        $scope.sale.service=$scope.findService;
                                        $log.log($scope.sale);
                             crudServiceOrders.create($scope.sale, 'sales').then(function (data) {
                           
                                    if (data['estado'] == true) {
                                        $scope.success = data['nombres'];
                                        $('#miventana1').modal('hide');
                                    alert('grabado correctamente');
                                       // $location.path('/services');
                                       //$route.reload(); 
                                        $scope.datosFactura(data['codFactura']);
                                        $log.log(data);


                                        crudServiceOrders.reportProWare('productsFavoritos',$scope.store.id,$scope.warehouse.id,'1').then(function(data){    
                                            $scope.favoritos=data;
                                        });
                                

                                    } else {
                                        $scope.errors = data;
                                    }
                            });

                            $scope.inicializar();
                        })    
                    });
                }


                $scope.datosFactura=function(codigoFactu){
                    alert("codigoFactu : "+codigoFactu);
                    crudServiceOrders.factura('detfactura',codigoFactu).then(function(data){    
                                            $scope.detVoices=data;
                                        });
                                        crudServiceOrders.factura('sales',codigoFactu).then(function(data){    
                                            $scope.headVoice=data;
                                            //alert(data.created_at);
                                            $scope.FechaCreado=new Date(data.created_at);
                                            $scope.anoFactura=""+$scope.FechaCreado.getFullYear();
                                            //$scope.diaFactura=""+$scope.FechaCreado.getDate();
                                            //$scope.convertirMes($scope.FechaCreado.getMonth()+1);
                                            $scope.insertar(Number(data.Total));
                                            if($scope.FechaCreado.getDate()<10){
                                                $scope.diaFactura="0"+$scope.FechaCreado.getDate();
                                            }else{
                                                $scope.diaFactura=""+$scope.FechaCreado.getDate();
                                            }
                                            if(($scope.FechaCreado.getMonth()+1)<10){
                                                $scope.mesActual="0"+($scope.FechaCreado.getMonth()+1);
                                            }else{
                                                $scope.mesActual=""+$scope.FechaCreado.getMonth()+1;
                                            }
                                            if(Number(data.numero)<10){
                                                $scope.numeroDocumento="000000"+data.numero;
                                            }else{
                                            if(Number(data.numero)<100){
                                               $scope.numeroDocumento="00000"+data.numero;
                                            }else{
                                            if(Number(data.numero)<1000){
                                               $scope.numeroDocumento="0000"+data.numero;
                                            }else{
                                            if(Number(data.numero)<10000){
                                               $scope.numeroDocumento="000"+data.numero;
                                            }else{
                                            if(Number(data.numero)<100000){
                                               $scope.numeroDocumento="00"+data.numero;
                                            }else{
                                            if(Number(data.numero)<1000000){
                                               $scope.numeroDocumento="0"+data.numero;
                                            }else{
                                            if(Number(data.numero)>=1000000){
                                               $scope.numeroDocumento=""+data.numero;
                                            }}}}}}}
                                            
                                        });
                                         
                                         if(Number($scope.cash1.cashHeader_id)<10){
                                                $scope.numCaja="00"+$scope.cash1.cashHeader_id;
                                            }else{
                                            if(Number($scope.cash1.cashHeader_id)<100){
                                               $scope.numCaja="0"+$scope.cash1.cashHeader_id;
                                            }else{
                                               $scope.numCaja=""+$scope.cash1.cashHeader_id;
                                            }}
                }
                $scope.convertirMes=function(valor){
                    switch(valor){
                        case 1:$scope.mesActual='Enero'; break;
                        case 2:$scope.mesActual='Febrero'; break;
                        case 3:$scope.mesActual='Marzo';break;  
                        case 4:$scope.mesActual='Abril';break; 
                        case 5:$scope.mesActual='Mayo';break;
                        case 6:$scope.mesActual='Junio';break;
                        case 7:$scope.mesActual='Julio';break;
                        case 8:$scope.mesActual='Agosto';break;
                        case 9:$scope.mesActual='Setiembre';break;
                        case 10:$scope.mesActual='Octubre';break;
                        case 11:$scope.mesActual='Noviembre';break;
                        case 12:$scope.mesActual='Diciembre';break;
                    }
                }
                $scope.actualizarCaja= function(){
                    $scope.detCashes={};
                    if ($scope.cashfinal.estado == 0) {       
                        //alert("Caja Cerrada");
                    }else{
                       /*crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                            var canCashes=data.total;
                            var pagActual=Math.ceil(canCashes/15);
                            crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){
                                $scope.cashes = data.data;
                                $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];
                               */
                                crudServiceOrders.paginate('ver_ventas',1).then(function (data){
                                    //$scope.detCashes = data.data;
                                    //crudServiceOrders.search('detCashesSale',$scope.cashfinal.id,1).then(function (data){
                                    
                                    $scope.detCashes = data.data;
                                    $scope.maxSize1 = 5;
                                    $scope.totalItems1 = data.total;
                                    $scope.currentPage1 = data.current_page;
                                    $scope.itemsperPage1 = 15;
                                });
                          //  });
                        //});
                    }
                        
                }
                $scope.pageChanged1 = function() {
                    if ($scope.query.length > 0) {
                        crudServiceOrders.search('detCashesSale',$scope.cashfinal.id,$scope.currentPage1).then(function (data){
                            $scope.detCashes = data.data;
                        });
                    }else{
                        crudServiceOrders.search('detCashesSale',$scope.cashfinal.id,$scope.currentPage1).then(function (data){
                            $scope.detCashes = data.data;
                        });
                    }
                };
                $scope.pageChangedZ = function() {
                   
                       
                        crudServiceOrders.buquedarapida('buquedarapida',$scope.store.id, $scope.warehouse.id,$scope.fechaConsulta,$scope.lineaId,$scope.materialId,$scope.buspro,$scope.currentPageZ).then(function (data) {                        
                        $scope.variants1 = data.data;
                    }); 
                    
                };
                /*
                /*
                $scope.createcash = function(){
                    $scope.mostrarAlmacenCaja();

                    $scope.detCash={};

                    $scope.detCash.cash_id=$scope.cashfinal.id; 
                    $scope.detCash.fecha=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                    $scope.detCash.hora=$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                    $scope.detCash.montoCaja=$scope.cashfinal.montoBruto;
                    $scope.detCash.cashMotive_id='1';
                    $scope.detCash.montoMovimientoCash=$scope.pago.cash;
                    $scope.detCash.montoMovimientoEfectivo=$scope.pago.tarjeta;
                    $scope.detCash.montoFinal=Number($scope.detCash.montoCaja)+$scope.detCash.montoMovimientoCash+$scope.detCash.montoMovimientoEfectivo;
                    $scope.detCash.estado='1';

                    $scope.cashfinal.ingresos=Number($scope.cashfinal.ingresos)+Number($scope.detCash.montoMovimiento); 
                    $scope.cashfinal.montoBruto=$scope.detCash.montoFinal;


                }*/
                $scope.estadoNotas = function () {
                    if($scope.sale.notas==""){
                        $scope.banderaNotas=true;
                    }else{
                        $scope.banderaNotas=false;     
                    }
                }
                $scope.delFavEst = function () {
                    //alert($scope.banderadeleteFavorito);
                    if($scope.banderadeleteFavorito){
                        $scope.banderadeleteFavorito=false;
                    }else{
                        $scope.banderadeleteFavorito=true;     
                    }
                }
                crudServiceOrders.AsignarCom = function (){
                    //alert("fdfdfdfdf");

                    $scope.atributoSelected=crudServiceOrders.getPres();

                        var fecha1 = $scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                            if(fecha1>=$scope.atributoSelected.FechaInicioDescuento && fecha1<=$scope.atributoSelected.FechaFinDescuento){
                                $scope.atributoSelected.descuento=Number($scope.atributoSelected.DescuentoConFecha);
                            }else{
                                $scope.atributoSelected.descuento=Number($scope.atributoSelected.DescuentoSinFecha);
                            }
                        if ($scope.atributoSelected.NombreAtributos!=undefined) {
                            //if ($scope.atributoSelected.equivalencia<=$scope.atributoSelected.Stock) {        
                                $scope.atributoSelected.cantidad=1;
                                //$scope.atributoSelected.descuento=0;
                                $scope.atributoSelected.subTotal=$scope.atributoSelected.cantidad*Number($scope.atributoSelected.precioProducto);
                                $scope.atributoSelected.precioVenta=Number($scope.atributoSelected.precioProducto);
                    
                                $scope.compras.push($scope.atributoSelected);  


                                $scope.bandera=true; 
                                    $scope.calcularmontos($scope.compras.length-1);

                                    if ($scope.compras[$scope.compras.length-1].descuento>0) {
                                        $scope.sale.montoTotalSinDescuento=$scope.sale.montoTotalSinDescuento+Number($scope.compras[$scope.compras.length-1].precioProducto)-$scope.compras[$scope.compras.length-1].precioVenta;
                                    };


                                $scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento+$scope.atributoSelected.subTotal;
                                $scope.recalcularCompra();
                                //$scope.sale.montoTotalSinDescuento=$scope.sale.montoTotal;
                                //$scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                                //$scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                                //}else{
                                  //  alert("STOCK menor a la Presentacion");    
                                //}
                            }else{
                                alert("Seleccione Producto Correctamente");
                                $scope.atributoSelected=undefined;
                            }
                    
                    $scope.atributoSelected=undefined;
                }

               $scope.tipoDeDocumentoGenerado=function(tipo,idFactura){
                      if(tipo=='F' || tipo=='B'){
                          $scope.datosFactura(idFactura);
                          alert("Factura generada XD");
                      }else{
                          $scope.imprimir_factura();
                      }
               }
                  
                $scope.pagar = function () {
                    $scope.validaDocumento();
                    if ($scope.sale.montoTotal==0) {
                        alert("Seleccione productos");
                    }else{
                        $scope.calcularVuelto();      
                    }
                }
                $scope.pagar1 = function () {
                    $scope.validaDocumento();
                    $scope.calcularVuelto();      
                }
                $scope.calcularVuelto = function () {
                    if ($scope.pago.tarjeta+$scope.pago.cash>$scope.sale.montoTotal) {
                        if ($scope.pago.tarjeta>$scope.sale.montoTotal) {
                            $scope.pago.tarjeta=$scope.sale.montoTotal;
                            alert("Pago tarjeta mayor compra");
                        }else{
                            $scope.sale.vuelto=(Number($scope.pago.tarjeta)+Number($scope.pago.cash)-Number($scope.sale.montoTotal));
                        }
                        //$scope.pago.cash=$scope.pago.cash-(Number($scope.pago.tarjeta)+Number($scope.pago.cash)-Number($scope.sale.montoTotal));
                    }else{
                         $scope.sale.vuelto=0;   
                    };
                    /*if($scope.pago.cash==undefined){
                        $scope.pago.cash=0;
                    }
                    if($scope.pago.tarjeta==undefined){
                        $scope.pago.tarjeta=0;
                    }*/
                }

                $scope.realizarPago = function () {
                
                crudServiceOrders.Comprueba_caj_for_user().then(function (data){
                      
                        if(data.id != undefined){
                         
                //$scope.mostrarAlmacenCaja();    
                if ($scope.cashfinal.estado=='1') {
                    $scope.salePayment.MontoTotal=$scope.sale.montoTotal;
                    $scope.salePayment.Acuenta=0;
                    $scope.salePayment.customer_id=$scope.sale.customer_id;

                    if($scope.pago.tarjeta>0 || $scope.pago.cash>=0){
                        if($scope.acuenta){
                            //Inicia Pago Credito 
                            //alert("credito");
                            if($scope.sale.customer_id==undefined){
                                alert("Elija Cliente");
                            }
                            else{
                                $scope.salePayment.estado=1;
                                $scope.sale.estado=1;
                                if ($scope.pago.tarjeta+$scope.pago.cash>=$scope.sale.montoTotal){
                                    alert("Usa Pago Contado");
                                }else{
                                if ($scope.radioModel!=undefined && $scope.pago.tarjeta==0) {
                                    alert("Elija monto Pago Tarjeta");
                                }else if($scope.radioModel!=undefined && $scope.pago.tarjeta>0){
                                    $scope.salePayment.Acuenta=$scope.salePayment.Acuenta+$scope.pago.tarjeta;
                                    $scope.saledetPayment.fecha=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate()+' '+$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                                    $scope.saledetPayment.monto=$scope.pago.tarjeta;
                                    $scope.saledetPayment.saleMethodPayment_id=$scope.radioModel;
                                    $scope.salePayment.Saldo=$scope.salePayment.MontoTotal-$scope.salePayment.Acuenta;
                                    $scope.saledetPayment.tipoPago="V";  
                                    $scope.saledetPayments.push($scope.saledetPayment);                                 
                                    $scope.saledetPayment={};
                                }
                                else if($scope.radioModel==undefined && $scope.pago.tarjeta>0){
                                    alert("Elija Tarjeta.");
                                    $scope.banderaPagosTarjeta=false;
                                }

                                if($scope.pago.cash>0&&$scope.banderaPagosTarjeta){
                                    $scope.salePayment.Acuenta=$scope.salePayment.Acuenta+$scope.pago.cash;
                                    $scope.saledetPayment.fecha=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate()+' '+$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                                    $scope.saledetPayment.monto=$scope.pago.cash;
                                    $scope.saledetPayment.saleMethodPayment_id='1';

                                    $scope.salePayment.Saldo=$scope.salePayment.MontoTotal-$scope.salePayment.Acuenta;
                                    $scope.saledetPayment.tipoPago="V";  
                                    $scope.saledetPayments.push($scope.saledetPayment);                                
                                    $scope.saledetPayment={};   
                                }

                                if ($scope.banderaPagosCash&&$scope.banderaPagosTarjeta) {
                                    $scope.sale.saledetPayments=$scope.saledetPayments;
                                    $scope.sale.salePayment=$scope.salePayment;
                                    var tipo='credito';

                                    $scope.createorder(tipo);

                                };
                                }
                            $scope.banderaPagosTarjeta=true;
                            }
                           //Termina Pago Credito 
                        }else{
                            //Inicio Pago Contado 
                            if ($scope.pago.tarjeta+$scope.pago.cash>$scope.sale.montoTotal) {
                                //alert("Vuelto : "+(Number($scope.pago.tarjeta)+Number($scope.pago.cash)-Number($scope.sale.montoTotal)));
                                //-------------Aca Calculo Vuelto----------------
                                $scope.pago.cash=$scope.pago.cash-(Number($scope.pago.tarjeta)+Number($scope.pago.cash)-Number($scope.sale.montoTotal));
                            };
                            if ($scope.pago.tarjeta+$scope.pago.cash<$scope.sale.montoTotal) {
                                alert("Pago menor a la compra");
                            }else if($scope.pago.tarjeta>$scope.sale.montoTotal){
                                alert("Pago tarjeta mayor a la compra");
                                $scope.pago.cash=0;
                            }else{
                            //alert("Contado");
                            //if (true) {};
                            $scope.salePayment.estado=0;
                            $scope.sale.estado=1;
                            if ($scope.radioModel!=undefined && $scope.pago.tarjeta==0) {
                                alert("Elija monto Pago Tarjeta");
                            }else if($scope.radioModel!=undefined && $scope.pago.tarjeta>0){
                                $scope.salePayment.Acuenta=$scope.salePayment.Acuenta+$scope.pago.tarjeta;
                                $scope.saledetPayment.fecha=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate()+' '+$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                                $scope.saledetPayment.monto=$scope.pago.tarjeta;
                                $scope.saledetPayment.saleMethodPayment_id=$scope.radioModel;
                                $scope.salePayment.Saldo=$scope.salePayment.MontoTotal-$scope.salePayment.Acuenta;
                                $scope.saledetPayment.tipoPago="V";  
                                $scope.saledetPayments.push($scope.saledetPayment);                                  
                                $scope.saledetPayment={};
                            }
                            else if($scope.radioModel==undefined && $scope.pago.tarjeta>0){
                                alert("Elija Tarjeta");
                                $scope.banderaPagosTarjeta=false;
                            }
                            if($scope.pago.cash>0 && $scope.banderaPagosTarjeta){
                                //alert($scope.banderaPagos);
                                $scope.salePayment.Acuenta=$scope.salePayment.Acuenta+$scope.pago.cash;
                                $scope.saledetPayment.fecha=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate()+' '+$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                                $scope.saledetPayment.monto=$scope.pago.cash;
                                $scope.saledetPayment.saleMethodPayment_id='1';
                                $scope.salePayment.Saldo=$scope.salePayment.MontoTotal-$scope.salePayment.Acuenta;
                                $scope.saledetPayment.tipoPago="V";  
                                $scope.saledetPayments.push($scope.saledetPayment);                                
                                $scope.saledetPayment={};   
                            }
                            if ($scope.banderaPagosCash&&$scope.banderaPagosTarjeta) {
                                $scope.sale.saledetPayments=$scope.saledetPayments;
                                $scope.sale.salePayment=$scope.salePayment;
                                //crear compra
                                var tipo='contado';
                                $scope.createorder(tipo);

                                };
                                $scope.banderaPagosTarjeta=true;
                            }
                        }
                    }
                    else{
                            alert("No puede realizar pago");

                    }
                    //--
                    
                    }else{
                        alert("Caja Cerrada");
                        //$scope.createcash();
                    }
                    }else{
                            alert("Usted no puede vender con esta caja");
                        }
               });

                }

               

                

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };

                
                
                $scope.createCustomer = function(){

                    if ($scope.customerCreateForm.$valid) {
                        crudServiceOrders.create($scope.customer, 'customers').then(function (data) {
                           
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
                    }
                    $scope.customer={};
                }
                $scope.varianteSkuSelected;
                $scope.varianteSkuSelected1=undefined;
                $scope.oferta=false;
                $scope.descuento10=0;
                $scope.getvariantSKU = function(size) {
                        $scope.fechaActual=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                        
                        crudServiceOrders.reportProWare('productsSearchsku',$scope.store.id,$scope.warehouse.id,$scope.varianteSkuSelected).then(function(data){    
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
                        crudServiceOrders.reportProWare('productsVariantes',$scope.store.id,$scope.warehouse.id,$scope.varianteSkuSelected1[0].vari).then(function(data){    
                            $scope.presentations = data;

                            var fecha1 = $scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                            if($scope.oferta==true){
                                  
                                 $scope.varianteSkuSelected1[0].descuento=Number($scope.descuento10);
                            }else{
                                  if(fecha1>=$scope.presentations[0].FechaInicioDescuento && fecha1<=$scope.presentations[0].FechaFinDescuento){
                                      $scope.varianteSkuSelected1[0].descuento=Number($scope.presentations[0].DescuentoConFecha);
                                  }else{
                                      $scope.varianteSkuSelected1[0].descuento=Number($scope.presentations[0].DescuentoSinFecha);
                                  }
                             }

                            if($scope.base){                
                                    $scope.varianteSkuSelected1[0].cantidad=1;
                                    //$scope.varianteSkuSelected1[0].descuento=0;
                                    $scope.varianteSkuSelected1[0].subTotal=$scope.varianteSkuSelected1[0].cantidad*Number($scope.varianteSkuSelected1[0].precioProducto);
                                    $scope.varianteSkuSelected1[0].precioVenta=Number($scope.varianteSkuSelected1[0].precioProducto);
                        
                                   $scope.compras.push($scope.varianteSkuSelected1[0]); 


                                   $scope.bandera=true; 
                                    $scope.calcularmontos($scope.compras.length-1);
                                   
                                    if ($scope.compras[$scope.compras.length-1].descuento>0) {
                                        $scope.sale.montoTotalSinDescuento=$scope.sale.montoTotalSinDescuento+Number($scope.compras[$scope.compras.length-1].precioProducto)-$scope.compras[$scope.compras.length-1].precioVenta;
                                    };
    
                                    $scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento+$scope.varianteSkuSelected1[0].subTotal;
                                    $scope.recalcularCompra();
                               
                                crudServiceOrders.confirmarVariante($scope.varianteSkuSelected1[0].vari,$scope.fechaActual).then(function(data){
                              
                              /*if(data.sku!=undefined && data.cantidad<2){
                                  if(confirm("Desea cargar oferta") == true){
                                      $scope.descuento10=data.descuento;
                                      $scope.varianteSkuSelected1=undefined;
                                      $scope.varianteSkuSelected=data.sku;
                                      $scope.oferta=true;
                                      $scope.compras[$scope.compras.length-1].oferta=1;
                                      $scope.getvariantSKU(); 
                                      }else{
                                         $scope.varianteSkuSelected1=undefined;
                                         $scope.varianteSkuSelected='';
                                         $scope.descuento10=0;
                                         $scope.oferta=false;
                                      }                             
                                      
                              }else{*/
                                  $scope.varianteSkuSelected1=undefined;
                                  $scope.varianteSkuSelected='';
                                  //$scope.descuento10=0;
                                  $scope.oferta=false;
                              //}
                          });
    
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



                $scope.atributoSelected=undefined;
                $scope.getAtributos = function(val) {
                  return crudServiceOrders.reportProWare('products',$scope.store.id,$scope.warehouse.id,val).then(function(response){
                    return response.map(function(item){
                      return item;
                    });
                  });
                };
                $scope.customersSelected=undefined;
                $scope.getcustomers = function(val) {
                  return crudServiceOrders.search('customersVenta',val,1).then(function(response){
                    return response.data.map(function(item){ 
                      return item;
                    });
                  });
                };

                $scope.editPromcion=function(row){
                      crudServiceOrders.update(row,'promocion').then(function(data)
                        {
                            if(data['estado'] == true){
                                alert('editado correctamente');
                            }else{
                                $scope.errors =data;
                            }
                        });
                }
  

               
                $scope.coloButton={};
                $scope.coloButton2={};
                $scope.cambiarColoButton=function(index,row){
                    //if(confirm("Esta seguro de querer Activar Oferta")==true){
                         $scope.coloButton[index]='info';
                         $scope.coloButton2[index]='default';
                         row.estado=1;

                         $scope.editPromcion(row);
                     //}
                }
                
                $scope.cambiarColoButton2=function(index,row){
                    //if(confirm("Esta seguro de querer Desactivar Oferta")==true){
                         $scope.coloButton2[index]='info';
                         $scope.coloButton[index]='default';
                         row.estado=0;
                         $scope.editPromcion(row);
                     //}
                }
                $scope.cambiarColoButtonini=function(index){
                         $scope.coloButton[index]='info';
                         $scope.coloButton2[index]='default';
                    
                }
                
                $scope.cambiarColoButtonini2=function(index){
                         $scope.coloButton2[index]='info';
                         $scope.coloButton[index]='default';
                         
                }
                $scope.selecionarCliente = function() {
                    if ($scope.customersSelected!=undefined) {
                        $scope.sale.customer_id=$scope.customersSelected.id;
                        $scope.sale.cliente=$scope.customersSelected.busqueda;
                        $scope.customersSelected=undefined;
                    };
                }
                $scope.asd = function($item, $model, $label) {
                    if ($scope.customersSelected!=undefined) {
                        $scope.sale.customer_id=$scope.customersSelected.id;
                        $scope.sale.cliente=$scope.customersSelected.busqueda;
                        $scope.customersSelected=undefined;
                    };
                }

                $scope.deleteCliente= function(){
                    $scope.sale.customer_id=undefined;
                    $scope.sale.cliente=undefined;
                    $scope.customersSelected=undefined;    
                }

                $scope.employeeSelected=undefined;
                $scope.getemployee = function(val) {
                  return crudServiceOrders.search('employeesVenta',val,1).then(function(response){
                    return response.data.map(function(item){
                      return item;
                    });
                  });
                };

                $scope.selecionarVendedor = function() {
                    if ($scope.employeeSelected!=undefined) {
                        $scope.sale.employee_id=$scope.employeeSelected.id;
                        $scope.sale.vendedor=$scope.employeeSelected.busqueda;
                        $scope.employeeSelected=undefined;
                    };
                }
                $scope.deleteVendedor= function(){
                    $scope.sale.employee_id=undefined;
                    $scope.sale.vendedor=undefined;
                    $scope.employeeSelected=undefined;    
                }
                //------------------------------------
                //------------------------------------
                //------------------------------------
                $scope.pagoCredito={};
                $scope.detPago={};
                $scope.createdetPayment = function(){
                    if($scope.detPago.monto<0){
                        alert("Numero no valido");
                    }else{
                        $scope.pagoCredito=$scope.payment[0]; 
                        crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                        var canCashes=data.total;
                        var pagActual=Math.ceil(canCashes/15);
                            //alert(pagActual);

                            crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){    
                                $scope.cashes = data.data;

                                $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];

                                if ($scope.cashfinal.estado=='1') {

                                    $scope.detCash={};
                                    $scope.mostrarAlmacenCaja();

                                    $scope.detCash.cash_id=$scope.cashfinal.id; 
                                    $scope.detCash.fecha=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                                    $scope.detCash.hora=$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                                    $scope.detCash.montoCaja=$scope.cashfinal.montoBruto;
                                    $scope.detCash.cashMotive_id='13';

                                    if ($scope.detPago.saleMethodPayment_id=='1') {
                                        $scope.detCash.montoMovimientoEfectivo=Number($scope.detPago.monto);
                                        $scope.detCash.montoMovimientoTarjeta=0;
                                        
                                    }else{
                                        $scope.detCash.montoMovimientoTarjeta=Number($scope.detPago.monto);
                                        $scope.detCash.montoMovimientoEfectivo=0;         
                                    }                        
                        
                                    $scope.detCash.montoFinal=Number($scope.detCash.montoCaja)+$scope.detCash.montoMovimientoTarjeta+$scope.detCash.montoMovimientoEfectivo;
                                    $scope.detCash.estado='1';

                                    $scope.cashfinal.ingresos=Number($scope.cashfinal.ingresos)+Number($scope.detCash.montoMovimientoTarjeta)+Number($scope.detCash.montoMovimientoEfectivo); 
                                    $scope.cashfinal.montoBruto=$scope.detCash.montoFinal;
                                    //////////////////////////////////////////////
                                    $scope.pagoCredito.movimiento=$scope.detCash; 
                                    $scope.pagoCredito.caja=$scope.cashfinal;



                    
                                    if(Number($scope.pagoCredito.Saldo)>=$scope.detPago.monto){
                                        $scope.pagoCredito.Acuenta=Number($scope.pagoCredito.Acuenta)+$scope.detPago.monto;
                                        $scope.pagoCredito.Saldo=Number($scope.pagoCredito.Saldo)-$scope.detPago.monto;

                                        $scope.detPago.salePayment_id=$scope.payment[0].id;
                                        $scope.detPago.fecha=new Date($scope.detPago.fecha);
                                        $scope.detPago.tipoPago="C";
                                        $scope.pagoCredito.detPayments=$scope.detPago;
                                        //--------------
                                        crudServiceOrders.byId($scope.pagoCredito.sale_id,'sales').then(function (data) {
                                                $scope.saleCredito=data;
                                                
                                                $scope.pagoCredito.sale=$scope.saleCredito;
                                                //---------------
                                            $scope.pagoCredito.tipo="sale";
                                            //---------------
                                            crudServiceOrders.create1($scope.pagoCredito, 'saledetPayments').then(function (data) {
                                                
                                                if (data['estado'] == true) {
                                
                                                    alert('grabado correctamente');
                                                    $scope.calcularPorcentaje();
                                                    //$scope.paginateDetPay($scope.detPago.salePayment_id);
                                                    $route.reload(); 
                                                    $scope.pagoCredito={};
                                                    $scope.detPago={};
                                                } else {
                                                    $scope.errors = data;
                                                     
                                                     //alert("--");
                                                }

                                            });
                                            //alert("--");
                                        });
                                    }else{
                                        alert("No se puede Realizar el pago");
                                    }

                            }else{alert("Caja Cerrada");}
                            });
                        }); 
                    }

                }
                
                //------------------------------------
                //------------------------------------
                //------------------------------------
                $scope.calcularmontos=function(index){
                    if($scope.compras[index].oferta==undefined){
                          $scope.compras[index].oferta=0;
                    }
                    $scope.fechaActual=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                        
                     
                    if($scope.compras[index].cantidad>($scope.compras[index].Stock-$scope.compras[index].stockPedidos-$scope.compras[index].stockSeparados)){
                        $scope.compras[index].cantidad=1;
                        alert("Cantidad excede el STOCK");
                    }
                    if($scope.compras[index].cantidad<1){
                       $scope.compras[index].cantidad=1;
                       alert("La cantidad debe ser mayor 0"); 
                    }
                    $scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento-$scope.compras[index].subTotal;

                    if($scope.bandera){
                        $scope.compras[index].precioVenta=((100-Number($scope.compras[index].descuento))*Number($scope.compras[index].precioProducto))/100;
                    }else{
                        $scope.compras[index].descuento=((Number($scope.compras[index].precioProducto)-Number($scope.compras[index].precioVenta))*100)/Number($scope.compras[index].precioProducto);
                    }
                    $scope.compras[index].subTotal=$scope.compras[index].cantidad*Number($scope.compras[index].precioVenta);

                    $scope.sale.montoTotal=$scope.sale.montoTotal+$scope.compras[index].subTotal;
                    $scope.recalcularCompra();
                    //$scope.sale.montoTotalSinDescuento=$scope.sale.montoTotal;
                    //$scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                    //$scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                    if($scope.compras[index].cantidad>=1){
                    crudServiceOrders.confirmarVariante($scope.compras[index].vari,$scope.fechaActual).then(function(data){
                          if(data.sku!=undefined && data.cantidad<=$scope.compras[index].cantidad ){
                               if($scope.compras[index].oferta==0){
                                  if(confirm("Desea cargar oferta") == true){
                                      $scope.descuento10=data.descuento;
                                      $scope.varianteSkuSelected1=undefined;
                                      $scope.varianteSkuSelected=data.sku;
                                      $scope.oferta=true;
                                      $scope.base=true;
                                      $scope.compras[index].oferta=1;
                                      $scope.getvariantSKU(); 
                                      }else{
                                        $scope.bandera=false; 
                                      }
                                }else{
                                        //$scope.promedioV=Number(Math.floor(Number($scope.compras[index].cantidad)/Number(data.cantidad)));
                                        //$scope.compras[index+1].cantidad=$scope.promedioV;
                                        //$scope.calcularmontos(index+1);
                                        //alert('hola');
                                        $scope.oferta=false;
                                        $scope.bandera=false;
                                        $scope.descuento10=0;
                                }
                               }
                          
                     });
                      }
                };

                $scope.aumentarCantidad= function(index){
                    if ($scope.compras[index].equivalencia!=undefined) {
                        $scope.compras[index].cantidad=$scope.compras[index].cantidad+1;
                        if($scope.compras[index].cantidad*$scope.compras[index].equivalencia>($scope.compras[index].Stock-$scope.compras[index].stockPedidos-$scope.compras[index].stockSeparados)){
                            alert("STOK INSUFICIENTE");
                            $scope.compras[index].cantidad=$scope.compras[index].cantidad-1;
                        }else{
                            //$scope.compras[index].cantidad=$scope.compras[index].cantidad+1;
                            $scope.calcularmontos(index);    
                        }
                    }else{
                        $scope.compras[index].cantidad=$scope.compras[index].cantidad+1;
                        $scope.calcularmontos(index);   
                    }
                                
                };
                $scope.disminuirCantidad= function(index){
                    $scope.compras[index].cantidad=$scope.compras[index].cantidad-1;
                    
                    $scope.calcularmontos(index);
                };
                $scope.aumentarPrecio= function(index){
                    $scope.compras[index].precioVenta=Number($scope.compras[index].precioVenta)+1;
                    $scope.calcularmontos(index);
                };
                $scope.disminuirPrecio= function(index){
                    $scope.compras[index].precioVenta=Number($scope.compras[index].precioVenta)-1;
                    $scope.calcularmontos(index);
                };
                $scope.aumentarDescuento= function(index){
                    $scope.compras[index].descuento=Number($scope.compras[index].descuento)+1;
                    $scope.bandera=true;
                    $scope.calcularmontos(index);
                };
                $scope.disminuirDescuento= function(index){
                    $scope.compras[index].descuento=Number($scope.compras[index].descuento)-1;
                    $scope.bandera=true;
                    $scope.calcularmontos(index);
                };
                $scope.keyUpDescuento= function(index){
                    $scope.bandera=true;
                    $scope.calcularmontos(index);
                };


                //-----------------------------------------------------
                $scope.aumentarTotalPedido= function(){
                    $scope.sale.montoTotal=Number($scope.sale.montoTotal)+1;

                     $scope.sale.descuento=((Number($scope.sale.montoTotalSinDescuento)-Number($scope.sale.montoTotal))*100)/Number($scope.sale.montoTotalSinDescuento);
                    $scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                    $scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                };
               
                $scope.disminuirTotalPedido= function(){
                    $scope.sale.montoTotal=Number($scope.sale.montoTotal)-1;                     
                    $scope.sale.descuento=((Number($scope.sale.montoTotalSinDescuento)-Number($scope.sale.montoTotal))*100)/Number($scope.sale.montoTotalSinDescuento);    
                    //$scope.recalcularCompra();
                    $scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                    $scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                };
                 $scope.keyUpTotalPedido= function(){
                     $scope.sale.descuento=((Number($scope.sale.montoTotalSinDescuento)-Number($scope.sale.montoTotal))*100)/Number($scope.sale.montoTotalSinDescuento);
                    $scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                    $scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                };
                $scope.keyUpDescuentoPedido= function(){
                    //$scope.sale.descuento=Number($scope.sale.descuento)+1;
                    $scope.sale.montoTotal=((100-Number($scope.sale.descuento))*Number($scope.sale.montoTotalSinDescuento))/100;
                    //$scope.recalcularCompra();
                    $scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                    $scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                };
                $scope.aumentarDescuentoPedido= function(){
                    $scope.sale.descuento=Number($scope.sale.descuento)+1;
                    $scope.sale.montoTotal=((100-Number($scope.sale.descuento))*Number($scope.sale.montoTotalSinDescuento))/100;
                    //$scope.recalcularCompra();
                    $scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                    $scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                };
                $scope.disminuirDescuentoPedido= function(){
                    $scope.sale.descuento=Number($scope.sale.descuento)-1;
                    $scope.sale.montoTotal=((100-Number($scope.sale.descuento))*Number($scope.sale.montoTotalSinDescuento))/100;    
                    //$scope.recalcularCompra();
                    $scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                    $scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                };
                
                $scope.sacarRow=function(index,total){
                    $scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento-$scope.compras[index].subTotal;
                    
                    $scope.recalcularCompra();

                    $scope.compras.splice(index,1);
                    if($scope.compras.length<1){
                        $scope.sale.descuento=0;    
                    }
                }

                $scope.recalcularCompra=function(){
                    $scope.sale.montoTotalSinDescuento=$scope.sale.montoTotal;
                    $scope.sale.montoTotal=((100-Number($scope.sale.descuento))*Number($scope.sale.montoTotalSinDescuento))/100;    

                    $scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                    $scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;  
                };

                

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

                     $scope.dynamicPopover2 = {
                     content: 'Hello, World!',
                     templateUrl: 'myPopoverTemplate2.html',
                     title: 'Preciodd',
                     title1: 'Descuento'
                     };
                     $scope.dynamicPopover6 = {
                     content: 'Hello, World!',
                     templateUrl: 'myPopoverTemplate6.html',
                     title: 'Notas',
                     };

                     $scope.tabs = [
                        { title:'Dynamic Title 1', content:'Dynamic content 1' },
                        { title:'Dynamic Title 2', content:'Dynamic content 2', disabled: true }
                    ];
                     $scope.alertMe = function() {
                        setTimeout(function() {
                         $window.alert('You\'ve selected the alert tab!');
                        });
                    };

                $scope.cargarFavoritos= function(row,size){                      
                        crudServiceOrders.reportProWare('productsVariantes',$scope.store.id,$scope.warehouse.id,row.vari).then(function(data){    
                        $scope.atributoSelected=row;
                        $scope.presentations = data;

                            var fecha1 = $scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                            if(fecha1>=$scope.presentations[0].FechaInicioDescuento && fecha1<=$scope.presentations[0].FechaFinDescuento){
                                $scope.atributoSelected.descuento=Number($scope.presentations[0].DescuentoConFecha);
                            }else{
                                $scope.atributoSelected.descuento=Number($scope.presentations[0].DescuentoSinFecha);
                            }
                        
                        if($scope.base){
                            //$scope.atributoSelected=undefined;
                            crudServiceOrders.reportProWare('productsFavoritos',$scope.store.id,$scope.warehouse.id,'1').then(function(data){    
                                        $scope.favoritos=data;
                                    });
                            
                            if ($scope.atributoSelected.NombreAtributos!=undefined) {
                                if (($scope.atributoSelected.Stock-$scope.atributoSelected.stockPedidos-$scope.atributoSelected.stockSeparados)>0) {         
                                    $scope.atributoSelected.cantidad=1;
                                    //$scope.atributoSelected.descuento=0;
                                    $scope.atributoSelected.subTotal=$scope.atributoSelected.cantidad*Number($scope.atributoSelected.precioProducto);
                                    $scope.atributoSelected.precioVenta=Number($scope.atributoSelected.precioProducto);
                    
                                    $scope.compras.push($scope.atributoSelected);  

                                    $scope.bandera=true; 
                                    $scope.calcularmontos($scope.compras.length-1);

                                    if ($scope.compras[$scope.compras.length-1].descuento>0) {
                                        $scope.sale.montoTotalSinDescuento=$scope.sale.montoTotalSinDescuento+Number($scope.compras[$scope.compras.length-1].precioProducto)-$scope.compras[$scope.compras.length-1].precioVenta;
                                    };

                                    $scope.sale.montoTotal=$scope.sale.montoTotalSinDescuento+$scope.atributoSelected.subTotal;
                                    $scope.recalcularCompra();

                                    //$scope.sale.montoTotalSinDescuento=$scope.sale.montoTotal;
                                    //$scope.sale.montoBruto=Number($scope.sale.montoTotal)/1.18;
                                    //$scope.sale.igv=$scope.sale.montoTotal-$scope.sale.montoBruto;
                                }else{
                                    alert("STOCK INSUFICIENTE");
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
                $scope.estadoFavorito=false;
                $scope.AddFavoritos= function(){
                    $scope.banderadeleteFavorito=true;
                    if($scope.atributoSelected==undefined){
                        alert("Seleccione Producto Correctamente");
                    }else{
                        if($scope.atributoSelected.favorite=='0'){
                            alert("El Producto ya es Favorito");
                        }else{
                            
                            if ($scope.atributoSelected.NombreAtributos!=undefined) { 
                               crudServiceOrders.byId($scope.atributoSelected.vari,'variants').then(function (data) {
                                    $scope.addFavorito=data;
                                    $scope.addFavorito.favorite=0;
                                    if ($scope.addFavorito!=null) {
                                        crudServiceOrders.editFavoritoId($scope.addFavorito,'variants').then(function (data) {
                                            $scope.estadoFavorito=data['estado'];
                                      
                                   
                                    
                                    $scope.addFavorito={};
                                    $scope.favoritos={};
                                  
                                    crudServiceOrders.reportProWare('productsFavoritos',$scope.store.id,$scope.warehouse.id,'1').then(function(data){    
                                        $scope.favoritos=data;
                                    }); 
                                      });
                                    };
                                     
                                });

                               

                            }else{
                                alert("Seleccione Producto Correctamente");
                                $scope.atributoSelected=undefined;
                            }
                            $scope.atributoSelected=undefined;                        
                        }
                    }                                  
                }
                
                $scope.validaDocumento=function(){
                $scope.estadoComoDocument=false;
                    if($scope.sale.comprobante==true )
                    {
                        if($scope.sale.cliente!=null){
                        $scope.sale.tipoDoc="F";
                               crudServiceOrders.numeracion("sales","F",$scope.cash1.cashHeader_id).then(function (data){
                                          //$scope.numActual="0000"+(Number(data.numFactura)+1);
                                        $scope.numeracionMostrar(data.numFactura);
                               });
                        }else{
                            $scope.sale.tipoDoc="B";
                               $scope.estadoComoDocument=true;
                               crudServiceOrders.numeracion("sales","B",$scope.cash1.cashHeader_id).then(function (data){
                                          //$scope.numActual="0000"+(Number(data.numFactura)+1);
                                       $scope.numeracionMostrar(data.numBoleta);
                               });
                        }
                    }else{
                        $scope.sale.tipoDoc="";
                        $scope.estadoComoDocument=false;
                    }
                    
                }
                $scope.numeracionMostrar=function(num){
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
                }
                $scope.cambioNumeracion=function(){
                    crudServiceOrders.numeracion("sales",$scope.sale.tipoDoc,$scope.cash1.cashHeader_id).then(function (data){
                                  if($scope.sale.tipoDoc=='B'){
                                     $scope.numeracionMostrar(data.numBoleta);
                                  }else{
                                     $scope.numeracionMostrar(data.numFactura);
                                  }
                                   
                        });
                }
                $scope.deleteFavoritos= function(row){  
                    
                        //alert($scope.atributoSelected.vari);
                        crudServiceOrders.byId(row.vari,'variants').then(function (data) {
                            $scope.addFavorito=data;
                                $scope.addFavorito.favorite=1;
                            crudServiceOrders.editFavoritoId($scope.addFavorito,'variants');

                            $scope.addFavorito={};
                            $scope.favoritos={};

                            crudServiceOrders.reportProWare('productsFavoritos',$scope.store.id,$scope.warehouse.id,'1').then(function(data){                                       
                                $scope.favoritos=data;
                            });
                        });                 
                }
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





                 $scope.open = function (size) {                   
                    $scope.cargarAtri(size);                   
                };

                $scope.close = function () {
                     $modal.dismiss('cancel');   
                };





                $scope.pageChanged = function() {
                    if ($scope.query.length > 0) {
                        crudServiceOrders.search('sales',$scope.query,$scope.currentPage).then(function (data){
                        $scope.orders = data.data;
                    });
                    }else{
                        crudServiceOrders.paginate('sales',$scope.currentPage).then(function (data) {
                            $scope.orders = data.data;
                        });
                    }
                };
                $scope.cargarAtributos = function() {
                    //alert("Hola : "+$scope.store.id+$scope.warehouse.id);
                    crudServiceOrders.reportProWare('products',$scope.store.id,$scope.warehouse.id).then(function (data) {
                            $scope.atributos = data;
                        });
                };
                $('#myTabs2 a').click(function (e) {
                          e.preventDefault()
                          $(this).tab('show')});


                

                socket.on('sales.update', function (data) {
                    $scope.orders=JSON.parse(data);
                });

                $scope.searchorder = function(){
                if ($scope.query.length > 0) {
                    crudServiceOrders.search('sales',$scope.query,1).then(function (data){
                        $scope.orders = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudServiceOrders.paginate('sales',1).then(function (data) {
                        $scope.orders = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };

                

                $scope.editorder = function(row){
                    $location.path('/sales/edit/'+row.id);
                };

                $scope.updateorder = function(){
                   if ($scope.orderCreateForm.$valid) {
                        crudServiceOrders.update($scope.sale,'sales').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/sales');
                            }else{
                                $scope.errors =data;
                            }
                        });
                    }
                };

                $scope.deleteorder = function(row){
                    $scope.sale = row;
                }

                $scope.cancelorder = function(){
                    $scope.sale = {};
                }
                $scope.calcularPorcentaje = function(){
                    $scope.payment[0].PorPagado=((Number($scope.payment[0].Acuenta)*100)/(Number($scope.payment[0].MontoTotal))).toFixed(2);
                    $scope.random();  
                }
                $scope.random = function() {
                    var type;

                    if ($scope.payment[0].PorPagado < 25) {
                      type = 'info';
                    } else if ($scope.payment[0].PorPagado < 50) {
                      type = 'success';
                    } else if ($scope.payment[0].PorPagado < 75) {
                      type = 'warning';
                    } else {
                      type = 'danger';
                    }

                    $scope.type = type;
                };
                $scope.paginateDetPay=function(idP){
                      crudServiceOrders.byId(idP,'saledetPayments').then(function (data) {
                        $scope.detPayments = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 5;

                    });
                }
                //$scope.currentPage;
                $scope.pagechan2=function(){
                    //alert($scope.currentPage);
                    crudServiceOrders.paginate('saledetPayments',$scope.currentPage).then(function (data) {
                            $scope.detPayments = data.data;
                        });
                }
                $scope.destroyorder = function(){
                    crudServiceOrders.destroy($scope.sale,'sales').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.sale = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }

                $scope.destroyPay = function(row){
                $scope.cash1.cashHeader_id=row.cashHeaders_id;
                crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                    var canCashes=data.total;
                    var pagActual=Math.ceil(canCashes/15);
                    crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){
                        $scope.cashes = data.data;
                        $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];

                        if ($scope.cashfinal.id==row.numCaja&&$scope.cashfinal.estado=='1') {
                            if(confirm("Esta segura de querer eliminar este pago!!!") == true){
                                $scope.payment[0].detpayment_id=row.id;

                                $scope.payment[0].saleMethodPayment=row.saleMethodPayment_id;
                                $scope.payment[0].montopayment=row.monto;
                                
                                $scope.payment[0].detCash_id=row.detCash_id;
                                crudServiceOrders.destroy($scope.payment[0],'salePayment').then(function(data){
                                if(data['estado'] == true){
                                        $scope.success = data['nombre'];
                                        $route.reload();

                                    }else{
                                        $scope.errors = data;
                                    }
                                });
                            }
                        }else{
                            alert("Caja Cerrada");
                        }

                    });
                })
}
            
            $scope.PagoAnterior;
            $scope.mostrarBtnGEd=false;
            $scope.check=false;
            $scope.filaenEdicion=false;
            $scope.editDetpayment=function(row){
                $scope.cash1.cashHeader_id=row.cashHeaders_id;
                $scope.opcionalRow=row.monto;
                crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                    var canCashes=data.total;
                    var pagActual=Math.ceil(canCashes/15);
                    crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){
                        $scope.cashes = data.data;
                        $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];
                        if ($scope.cashfinal.id==row.numCaja&&$scope.cashfinal.estado=='1') {
                            $scope.detPago=row;
                            $scope.detPago.fecha=new Date(row.fecha);
                            $scope.detPago.monto=Number(row.monto);

                            $scope.detPago.detpayment_id=row.id;
                            $scope.detPago.detCash_id=row.detCash_id;

                            $scope.mostrarBtnGEd=true;
                        }else{
                            alert("Caja Cerrada");    
                        }
                    });
                });    
            }  
            $scope.editPayment = function(){
                if (Number($scope.payment[0].Acuenta)-Number($scope.opcionalRow) +Number($scope.detPago.monto) <= Number($scope.payment[0].MontoTotal)) {
                    crudServiceOrders.update($scope.detPago,'editdetpatmentSale').then(function(data){
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }else{
                    alert("Pago Mayor al Saldo");
                }
            }
            $scope.cancel = function(){
                $route.reload(); 
                //$scope.detPago={};
                //$scope.mostrarBtnGEd=false; 
            }
            $scope.canPedido= function() {
                    $scope.banderaModificar=!$scope.banderaModificar;
                    if ($scope.cancelPedido) {
                        if ($scope.payment[0].Acuenta>0) {
                            $scope.montosaldo=$scope.payment[0].Acuenta;
                            $scope.banderaDevolucion=true
                            $scope.payment[0].Acuenta=$scope.payment[0].Acuenta-$scope.payment[0].Acuenta;
                            $scope.payment[0].Saldo=$scope.payment[0].MontoTotal-$scope.payment[0].Acuenta;
                            //alert($scope.payment[0].Acuenta);
                        }else{$scope.banderaDevolucion=false}
                        $scope.estadoAnulado=true;
                        //++for (var i = $scope.detOrders.length - 1; i >= 0; i--) {
                            //$scope.detOrders[i].estad=true;
                            //+++if ($scope.detOrders[i].canPendiente>0) {
                          //++      $scope.detOrders[i].estad=true;
                            //++}else{
                                //++$scope.detOrders[i].estad=false;       
                            //++}

                            //++if ($scope.detOrders[i].estado==1 && $scope.detOrders[i].canPendiente==0){
                            //++    $scope.detOrders[i].estad1=true;
                            //++}else{
                            //++    $scope.detOrders[i].estad1=false; 
                            //++}
                        //}; 
                    }else{
                        $route.reload();
                        //++$scope.estadoAnulado=false;
                       // $scope.order1.estado=1;
                        //++for (var i = $scope.detOrders.length - 1; i >= 0; i--) {
                            //++if ($scope.detOrders[i].canPendiente>0){
                                //++$scope.detOrders[i].estad=false;
                            //++}

                            //++if ($scope.detOrders[i].estado==1 && $scope.detOrders[i].canPendiente==0){
                                //++$scope.detOrders[i].estad1=true;
                            //++}else{
                             //++   $scope.detOrders[i].estad1=false; 
                            //++}   
                        //++}
                    };

                //});
                }
    $scope.insertar=function(num){
        //alert(Math.floor(num));
        if((num-Math.floor(num))!=0){
            $scope.DecripcionTotal=$scope.doThings(Math.floor(num))+" CON "+((num-Math.floor(num))*100).toFixed(0)+"/100 NUEVOS SOLES";
            
        }else{
            $scope.DecripcionTotal=$scope.doThings(Math.floor(num))+" ";
        }
        
    }
    $scope.doThings=function(valor){
        //Limite
        if(valor >2000000)
            {return "DOS MILLONES";}
        
        switch(valor){
            case 0: return "CERO";
            case 1: return "UNO"; //UNO
            case 2: return "DOS";
            case 3: return "TRES";
            case 4: return "CUATRO";
            case 5: return "CINCO"; 
            case 6: return "SEIS";
            case 7: return "SIETE";
            case 8: return "OCHO";
            case 9: return "NUEVE";
            case 10: return "DIEZ";
            case 11: return "ONCE"; 
            case 12: return "DOCE"; 
            case 13: return "TRECE";
            case 14: return "CATORCE";
            case 15: return "QUINCE";
            case 20: return "VEINTE";
            case 30: return "TREINTA";
            case 40: return "CUARENTA";
            case 50: return "CINCUENTA";
            case 60: return "SESENTA";
            case 70: return "SETENTA";
            case 80: return "OCHENTA";
            case 90: return "NOVENTA";
            case 100: return "CIEN";
            
            case 200: return "DOSCIENTOS";
            case 300: return "TRESCIENTOS";
            case 400: return "CUATROCIENTOS";
            case 500: return "QUINIENTOS";
            case 600: return "SEISCIENTOS";
            case 700: return "SETECIENTOS";
            case 800: return "OCHOCIENTOS";
            case 900: return "NOVECIENTOS";
            
            case 1000: return "MIL";
            
            case 1000000: return "UN MILLON";
            case 2000000: return "DOS MILLONES";
        }
        if(valor<20){
            //System.out.println(">15");
            return "DIECI"+ $scope.doThings(valor-10);
        }
        if(valor<30){
            //System.out.println(">20");
            return "VEINTI" + $scope.doThings(valor-20);
        }
        if(valor<100){
            //System.out.println("<100"); 
            //alert((Math.floor(valor/10))*10);
            return $scope.doThings((Math.floor(valor/10))*10) + " Y " + $scope.doThings(valor%10);
        }        
        if(valor<200){
            //System.out.println("<200"); 
            return "CIENTO " + $scope.doThings( valor - 100 );
        }         
        if(valor<1000){
            //System.out.println("<1000");
            return $scope.doThings((Math.floor(valor/100))*100) + " " + $scope.doThings(valor%100);
        } 
        if(valor<2000){
            //System.out.println("<2000");
            return "MIL " + $scope.doThings( valor % 1000 );
        } 
        if(valor<1000000){
            $scope.descri="";
            //System.out.println("<1000000");
            $scope.descri = $scope.doThings(Math.floor(valor/1000)) + " MIL" ;
            if(valor % 1000!=0){
                //System.out.println(var);
               // alert(valor % 1000);
                $scope.descri += " " + $scope.doThings(valor % 1000);
            }
            return $scope.descri;
        }
        if(valor<2000000){
            return "UN MILLON " + $scope.doThings( valor % 1000000 );
        } 
        return "";
    }    
                $scope.grabarCanPedido= function() {
                    if ($scope.estadoAnulado) {
                        $scope.order1.estado=3;
                    }else{
                      $scope.order1.estado=1;
                    }
                    //$scope.estadoAnulado=false;
                       // $scope.order1.estado=1;
                    //++for (var i = $scope.detOrders.length - 1; i >= 0; i--) {
                            //------------------------------------------------
                            //++if ($scope.detOrders[i].estad==true && $scope.detOrders[i].estado==0 && $scope.detOrders[i].canPendiente!=0) {
                                //++$scope.detOrders[i].estado=1;
                                //++$scope.banderaCancel=true;
                            //++}
                            //++if ($scope.detOrders[i].estad==false && $scope.detOrders[i].estado==1 && $scope.detOrders[i].canPendiente!=0) {
                                //++$scope.detOrders[i].estado=0;
                                //++$scope.banderaCancel=true;
                            //++}
                    //++}
                    $scope.order1.detOrder=$scope.detOrders;
                    $scope.order1.payment=$scope.payment[0];
                    $scope.createsalidaCaja();
                    

                    //crudServiceOrders.update($scope.payment[0],'SalePayment').then(function (data){
                    //})

                }
                $scope.createsalidaCaja = function(tipo){
                    if ($scope.cash1.cashHeader_id==undefined) {
                        alert("Elija Caja");
                    }else{
                        crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,1).then(function (data){
                            var canCashes=data.total;
                            var pagActual=Math.ceil(canCashes/15);
                            crudServiceOrders.search('cashes',$scope.cash1.cashHeader_id,pagActual).then(function (data){
                                $scope.cashes = data.data;
                                $scope.cashfinal=$scope.cashes[$scope.cashes.length-1];
                                if ($scope.cashfinal.estado=='1') {
                                    $scope.detCash={};
                                    $scope.mostrarAlmacenCaja();

                                    $scope.detCash.cash_id=$scope.cashfinal.id; 
                                    $scope.detCash.fecha=$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                                    $scope.detCash.hora=$scope.date.getHours()+':'+$scope.date.getMinutes()+':'+$scope.date.getSeconds();
                                    $scope.detCash.montoCaja=$scope.cashfinal.montoBruto;
                    
                                    //$scope.detCash.montoMovimientoTarjeta=Number($scope.pago.tarjeta);
                                    $scope.detCash.montoMovimientoEfectivo=Number($scope.montosaldo);
                                    $scope.detCash.montoFinal=Number($scope.detCash.montoCaja)-$scope.detCash.montoMovimientoEfectivo;
                                    $scope.detCash.estado='1'; 
                                    //alert(tipo);
                                    $scope.detCash.cashMotive_id='18';
                    

                                    $scope.cashfinal.gastos=Number($scope.cashfinal.gastos)+Number($scope.detCash.montoMovimientoEfectivo); 
                                    $scope.cashfinal.montoBruto=$scope.detCash.montoFinal;
                                    //////////////////////////////////////////////
                                    $scope.order1.movimiento=$scope.detCash;
                                    $scope.order1.caja=$scope.cashfinal;

                                    crudServiceOrders.update($scope.order1,'sales').then(function (data){
                                        $location.path('/sales');
                                    });
                                }else{
                                    alert("Caja Cerrada");
                                }


                            });
                        });
                    }
                }
                //-------------------generar reporte-------------
                $scope.reporte={};
                $scope.reporteCliente = function(){
                    var fInicio=$scope.reporte.fechaInicio.getFullYear()+'-'+($scope.reporte.fechaInicio.getMonth()+1)+'-'+$scope.reporte.fechaInicio.getDate();
                    var fFin=$scope.reporte.fechaFin.getFullYear()+'-'+($scope.reporte.fechaFin.getMonth()+1)+'-'+$scope.reporte.fechaFin.getDate();

                    crudServiceOrders.reportcliente('Reportsales',fInicio,fFin);

                }
                //--------------------------------------------------
                $scope.buspro=0;
                $scope.cargarConsulta = function(){
                    $scope.fechaConsulta = ''+$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                    $scope.lineaId=0;$scope.materialId=0;

                    crudServiceOrders.buquedarapida('buquedarapida',$scope.store.id, $scope.warehouse.id,$scope.fechaConsulta,$scope.lineaId,$scope.materialId,$scope.buspro,1).then(function (data) {                        
                        $scope.variants1 = data.data;
                        $scope.maxSizeZ = 5;
                        $scope.totalItemsZ = data.total;
                        $scope.currentPageZ = data.current_page;
                        $scope.itemsperPageZ = 15;
                    }); 
                    crudServiceOrders.all('TraerModelos').then(function (data) {                        
                        $scope.types = data.data;
                    });
                    crudServiceOrders.all('brands').then(function (data) {                        
                        $scope.brands = data.data;
                    });      
                }
                $scope.cargarConsul= function(){
                    
                    if ($scope.lineaId==undefined) {$scope.lineaId=0;};
                    if ($scope.materialId==undefined) {$scope.materialId=0;};              
                    $scope.fechaConsulta = ''+$scope.date.getFullYear()+'-'+($scope.date.getMonth()+1)+'-'+$scope.date.getDate();
                    if ($scope.busProducto==undefined || $scope.busProducto=='') {$scope.buspro=0;}
                        else{$scope.buspro=$scope.busProducto}

                    crudServiceOrders.buquedarapida('buquedarapida',$scope.store.id, $scope.warehouse.id,$scope.fechaConsulta,$scope.lineaId,$scope.materialId,$scope.buspro,1).then(function (data) {                        
                        $scope.variants1 = data.data;
                        $scope.maxSizeZ = 5;
                        $scope.totalItemsZ = data.total;
                        $scope.currentPageZ = data.current_page;
                        $scope.itemsperPageZ = 15;
                    }); 
                }
                $scope.documento={};
                $scope.detDocumento=[];
                $scope.imprimir_factura=function(){
                   crudServiceOrders.imprimir_factura($scope.documento).then(function (data) {
                          
                            if (data['estado'] == true) {
                                alert('tiket Generado');
                                 $route.reload();
                            } else {
                                $scope.errors = data;

                            }
                        });
                }
                $scope.traerDoumento=function(row){
                  
                  crudServiceOrders.search('datosDocumento',row.idDocu).then(function (data) {                        
                        $scope.documento = data;
                         
                          
                    }); 
                   crudServiceOrders.factura('detfactura',row.idDocu).then(function (data) {                        
                        $scope.detDocumento = data;
                              //$location.path('sales/create#tab_7');
                         });
                }
                $scope.validar=function(data){
                
                    $scope.promocion.productBase_id=data.vari;
                
                }
                 $scope.validar1=function(data){
                
                    $scope.promocion.product_id=data.vari;
                
                }
                $scope.createPromotion=function(){
                    if ($scope.promocionCreateForm.$valid) {
                     crudServiceOrders.create($scope.promocion, 'promocion').then(function (data) {
                          
                            if (data['estado'] == true) {
                                alert('grabado correctamente');
                                 $route.reload();
                            } else {
                                $scope.errors = data;

                            }
                        });
                 }
                }
                $scope.cargarPromociones=function(){
                    crudServiceOrders.paginate('promocion',1).then(function (data) {
                        $scope.promociones = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });

                }
                $scope.formPromocion=false;
                $scope.mostrarformProm=function(){
                       $scope.formPromocion=!$scope.formPromocion;
                }
                $scope.DropPromotions=function(row){
                       
                       crudServiceOrders.destroy(row,'promocion').then(function(data)
                    {
                        if(data['estado'] == true){                            
                            
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }
        
                $scope.buscarporProduct=function(query){
                    if ($scope.lineaId==undefined) {$scope.lineaId=0;};
                    if ($scope.materialId==undefined) {$scope.materialId=0;};              
                    
                    crudServiceOrders.buquedarapida('buquedarapida',$scope.store.id, $scope.warehouse.id,$scope.fecha,$scope.lineaId,$scope.materialId,query).then(function (data) {                        
                        $scope.variants1 = data;
                    }); 
                }

            }]);
})();