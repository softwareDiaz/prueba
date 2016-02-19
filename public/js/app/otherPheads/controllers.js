(function(){
    angular.module('otherPheads.controllers',[])
        .controller('OtherPheadController',['$scope', '$routeParams','$location','crudService','socketService' ,'$filter','$route','$log',
            function($scope, $routeParams,$location,crudService,socket,$filter,$route,$log){
                $scope.otherPheads = [];
                $scope.otherPhead = {};
                $scope.otherPdetail = {};
                $scope.otherPdetails = [];
                $scope.errors = null;
                $scope.success;
                $scope.query = '';
                $scope.otherPhead.tipoDoc="O";
                $scope.otherPhead.fecha=new Date();

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };
         
                $scope.pageChanged = function() {
                    if ($scope.query.length > 0) {
                        crudService.search('otherPheads',$scope.query,$scope.currentPage).then(function (data){
                        $scope.otherPheads = data.data;
                    });
                    }else{
                        crudService.paginate('otherPheads',$scope.currentPage).then(function (data) {
                            $scope.otherPheads = data.data;
                        });
                    }
                };


                var id = $routeParams.id;

                if(id)
                {
                	crudService.byId(id,'otherPdetails').then(function (data) {
                          	    	$scope.otherPdetails=data.data;
                          });
                    crudService.byId(id,'otherPheads').then(function (data) {
                        $scope.otherPhead = data;
                        $scope.otherPhead.fecha=new Date(data.fecha);
                        $scope.otherPhead.descuento=Number(data.descuento);
                        $scope.otherPhead.MontoSubTotal=Number(data.MontoSubTotal);
                        $scope.otherPhead.igv=Number(data.igv);
                        $scope.otherPhead.BaseImponible=Number(data.BaseImponible);
                        $scope.otherPhead.MontoTotal=Number(data.MontoTotal);
                        if($scope.otherPhead.checkIgv==1){$scope.otherPhead.checkIgv=true;}
                        else{
                        	$scope.otherPhead.checkIgv=false;
                        }

                    });
                }else{
                    crudService.paginate('otherPheads',1).then(function (data) {
                        $scope.otherPheads = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });
                }

                socket.on('otherPhead.update', function (data) {
                    $scope.otherPheads=JSON.parse(data);
                });

                $scope.searchotherPhead = function(){
                if ($scope.query.length > 0) {
                    crudService.search('otherPheads',$scope.query,1).then(function (data){
                        $scope.otherPheads = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudService.paginate('otherPheads',1).then(function (data) {
                        $scope.otherPheads = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };
                 $scope.otherPhead.MontoSubTotal=0;
                 $scope.otherPhead.descuento=0;
                $scope.llenarDetalles=function(){  
                	 if ($scope.otherPdetail.cantidad>0 && $scope.otherPdetail.PrecioUnit>0
                	 	&& $scope.otherPdetail.PrecioFinal>0 && $scope.otherPdetail.descripcion.length>0) {
                      $scope.otherPhead.MontoSubTotal=$scope.otherPhead.MontoSubTotal+$scope.otherPdetail.PrecioFinal; 
                      $scope.activIgvtotal();
                      $scope.otherPdetails.push($scope.otherPdetail);
                      $scope.otherPdetail = {};
                  }else{
                  	alert("llene todos los campos");
                  }
                }
                $scope.calcMontosFinales=function(){
                	  //$scope.otherPhead.MontoSubTotal=$scope.otherPhead.MontoSubTotal+$scope.otherPdetail.PrecioFinal; 
                      $scope.activIgvtotal();
                      
                }
                $scope.otherPhead.checkIgv=false;
                $scope.activIgvtotal=function(){
                	if($scope.otherPhead.checkIgv==true){
                      $scope.total=Number(($scope.otherPhead.MontoSubTotal-(($scope.otherPhead.MontoSubTotal*$scope.otherPhead.descuento)/100)).toFixed(2))
                      $scope.otherPhead.igv=Number(($scope.total-($scope.total/1.18)).toFixed(2));
                      $scope.otherPhead.BaseImponible=Number(($scope.total/1.18).toFixed(2));             
                      $scope.otherPhead.MontoTotal=$scope.otherPhead.BaseImponible+$scope.otherPhead.igv;
                	}else{
                	  $scope.total=Number(($scope.otherPhead.MontoSubTotal-(($scope.otherPhead.MontoSubTotal*$scope.otherPhead.descuento)/100)).toFixed(2))
                      $scope.otherPhead.igv=$scope.total*0.18;
                      $scope.otherPhead.BaseImponible=$scope.otherPhead.MontoSubTotal;             
                      $scope.otherPhead.MontoTotal=$scope.otherPhead.BaseImponible+$scope.otherPhead.igv;
                     
                	}
                }
                $scope.calcMontosFinales2=function(){
                	  //$scope.otherPhead.MontoSubTotal=$scope.otherPhead.MontoSubTotal+$scope.otherPdetail.PrecioFinal; 
                	  var tot=$scope.otherPhead.MontoSubTotal-$scope.otherPhead.MontoTotal;
                	  //alert(tot);
                      $scope.otherPhead.descuento=Number(((tot/$scope.otherPhead.MontoSubTotal)*100).toFixed(2));
                     // $scope.total=Number(($scope.otherPhead.MontoSubTotal-(($scope.otherPhead.MontoSubTotal*$scope.otherPhead.descuento)/100)).toFixed(2))
                      $scope.activIgvtotal();            
                      //$scope.otherPhead.MontoTotal=$scope.otherPhead.BaseImponible+$scope.otherPhead.igv;
                      
                }
                $scope.sacarRow=function(index,monto){
                	
                	$scope.otherPhead.MontoSubTotal=Number(($scope.otherPhead.MontoSubTotal-monto).toFixed(2));
                	if($scope.otherPhead.checkIgv==true){
                      $scope.total=Number(($scope.otherPhead.MontoSubTotal-(($scope.otherPhead.MontoSubTotal*$scope.otherPhead.descuento)/100)).toFixed(2))
                      $scope.otherPhead.igv=Number(($scope.total-($scope.total/1.18)).toFixed(2));
                      $scope.otherPhead.BaseImponible=Number(($scope.total/1.18).toFixed(2));             
                      $scope.otherPhead.MontoTotal=$scope.otherPhead.BaseImponible+$scope.otherPhead.igv;
                	}else{
                	  $scope.total=Number(($scope.otherPhead.MontoSubTotal-(($scope.otherPhead.MontoSubTotal*$scope.otherPhead.descuento)/100)).toFixed(2))
                      $scope.otherPhead.igv=$scope.total*0.18;
                      $scope.otherPhead.BaseImponible=$scope.otherPhead.MontoSubTotal;             
                      $scope.otherPhead.MontoTotal=$scope.otherPhead.BaseImponible+$scope.otherPhead.igv;
                     
                	}
                	$scope.otherPdetails.splice(index,1);
                }
                $scope.clalcSubtotal=function(){
                	if($scope.otherPdetail.cantidad!=undefined && $scope.otherPdetail.PrecioUnit!=undefined){
                	$scope.otherPdetail.PrecioFinal=Number(($scope.otherPdetail.cantidad*$scope.otherPdetail.PrecioUnit).toFixed(2));
                    }
                }
                 $scope.validanomMarca=function(texto){
                
                   if(texto!=undefined){
                        crudService.validar('otherPheads',texto).then(function (data){
                        $scope.otherPhead = data;
                        if($scope.otherPhead!=null){
                           alert("Usted no puede crear dos Marcas con el mismo nombre");
                           $scope.otherPhead.nombre=''; 
                           $scope.otherPhead.shortname=''; 
                        }
                    });
                    }
               }

                $scope.createotherPhead = function(){
                    //$scope.atribut.estado = 1;
                    $scope.otherPhead.fecha=$scope.otherPhead.fecha.getFullYear()+'-'+($scope.otherPhead.fecha.getMonth()+1)+'-'+$scope.otherPhead.fecha.getDate()+' '+$scope.otherPhead.fecha.getHours()+':'+$scope.otherPhead.fecha.getMinutes()+':'+$scope.otherPhead.fecha.getSeconds();
                    $scope.otherPhead.detalles=$scope.otherPdetails;
                    //if ($scope.otherPheadCreateForm.$valid) {
                        crudService.create($scope.otherPhead, 'otherPheads').then(function (data) {
                          
                            if (data['estado'] == true) {
                                $scope.success = data['nombres'];
                                alert('grabado correctamente');
                                $location.path('/otherPheads');

                            } else {
                                $scope.errors = data;

                            }
                        });
                    //}
                }


                $scope.editotherPhead = function(row){
                    $location.path('/otherPheads/edit/'+row.id);
                };

                $scope.updateotherPhead = function(){
                    $scope.otherPhead.fecha=$scope.otherPhead.fecha.getFullYear()+'-'+($scope.otherPhead.fecha.getMonth()+1)+'-'+$scope.otherPhead.fecha.getDate()+' '+$scope.otherPhead.fecha.getHours()+':'+$scope.otherPhead.fecha.getMinutes()+':'+$scope.otherPhead.fecha.getSeconds();
                    $scope.otherPhead.detalles=$scope.otherPdetails;
                    //if ($scope.otherPheadCreateForm.$valid) {
                        crudService.update($scope.otherPhead,'otherPheads').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/otherPheads');
                            }else{
                                $scope.errors =data;
                            }
                        });
                   // }
                };

                $scope.deleteotherPhead = function(row){
                    $scope.otherPhead = row;
                }

                $scope.cancelotherPhead = function(){
                    $scope.otherPhead = {};
                }

                $scope.destroyotherPhead = function(){

                    crudService.destroy($scope.otherPhead,'otherPheads').then(function(data)
                    {
                        if(data['estado'] == true){
                            
                            $scope.otherPhead = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }
            }]);
})();
