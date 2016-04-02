(function(){
    angular.module('materials.controllers',[])
        .controller('MaterialsController',['$scope', '$routeParams','$location','crudService','socketService' ,'$filter','$route','$window','$log',
            function($scope, $routeParams,$location,crudService,socket,$filter,$route,$window,$log){
                $scope.materials = [];
                $scope.material = {};
                $scope.mesActuales=[];
                $scope.mesActual={};
                $scope.errors = null;
                $scope.success;
                $scope.query = '';

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };

                $scope.pageChanged = function() {
                    if ($scope.query.length > 0) {
                        crudService.search('materials',$scope.query,$scope.currentPage).then(function (data){
                        $scope.materials = data.data;
                    });
                    }else{
                        crudService.paginate('materials',$scope.currentPage).then(function (data) {
                            $scope.materials = data.data;
                        });
                    }
                };


                var id = $routeParams.id;

                if(id)
                {
                    crudService.byId(id,'materials').then(function (data) {
                        $scope.material = data;
                    });
                }else{
                    crudService.paginate('materials',1).then(function (data) {
                        $scope.materials = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });
                }

                socket.on('material.update', function (data) {
                    $scope.materials=JSON.parse(data);
                });

                if($location.path() == '/materials/listPreciDolar') {
                    crudService.paginate('preDolar',1).then(function (data) {
                        $scope.mesActuales = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });
                }
                  if($location.path() == '/materials/editPreDolar/'+$routeParams.id) {
                      $scope.mes=parseInt(id.substring(0,2));

                      crudService.byId($routeParams.id,'preDolar').then(function (data) {
                        $scope.mesActuales = data;

                    });
                  }
                $scope.editPReDolar=function(row){
                    $location.path('/materials/editPreDolar/'+row.mes);
                }
                $scope.searchMaterial = function(){
                if ($scope.query.length > 0) {
                    crudService.search('materials',$scope.query,1).then(function (data){
                        $scope.materials = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudService.paginate('materials',1).then(function (data) {
                        $scope.materials = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };

                $scope.createMaterial = function(){
                    //$scope.atribut.estado = 1;
                    if ($scope.materialCreateForm.$valid) {
                        crudService.create($scope.material, 'materials').then(function (data) {
                          
                            if (data['estado'] == true) {
                                $scope.success = data['nombres'];
                                alert('grabado correctamente');
                                $location.path('/materials');

                            } else {
                                $scope.errors = data;

                            }
                        });
                    }
                }


                $scope.editMaterial = function(row){
                    $location.path('/materials/edit/'+row.id);
                };

                $scope.updateMaterial = function(){

                    if ($scope.materialCreateForm.$valid) {
                        crudService.update($scope.material,'materials').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/materials');
                            }else{
                                $scope.errors =data;
                            }
                        });
                    }
                };

                $scope.deleteMaterial = function(row){
                    $scope.material = row;
                }

                $scope.cancelMaterial = function(){
                    $scope.material = {};
                }
                

                $scope.llenardolar=function(){
                    var fecha=new Date();
                    var dato="";
                    if($scope.mes<10){dato='0'+$scope.mes+"-"+fecha.getFullYear();}else{dato=$scope.mes+"-"+fecha.getFullYear();}
                    //alert(dato);
                    crudService.byId(dato,'preDolar').then(function (data) {
                       
                    if(data[0]!=undefined){
                         alert("Error usted ya a registrado precios para este mes seleccione otro mes");
                         $scope.mes="";
                    }else{
                    $scope.mesActuales=[];
                    
                    var dia=0;
                    var año=fecha.getFullYear();
                    var f1='01/'+$scope.mes+'/'+año;
                    if($scope.mes==12){var f2='01/01/'+(parseInt(año)+1);}else{var f2='01/'+(parseInt($scope.mes)+1)+'/'+año;}
                    
                    
                   var cantdias=$scope.restarFechas(f1,f2); 
                    for (i = 0; i<parseInt(cantdias); i++) { 
                             //$scope.mesActual[i.fecha1='';
                             dia=dia+1;
                             if($scope.mes<10){$scope.mes1='0'+$scope.mes;}else{$scope.mes1=$scope.mes;}
                             if(dia<10){
                                $scope.mesActual.fecha=fecha.getFullYear()+'/'+$scope.mes1+'/0'+dia;
                                $scope.mesActual.fecha2='0'+dia+'/'+$scope.mes1+'/'+fecha.getFullYear();
                             }else{
                                $scope.mesActual.fecha=fecha.getFullYear()+'/'+$scope.mes1+'/'+dia;
                                $scope.mesActual.fecha2=dia+'/'+$scope.mes1+'/'+fecha.getFullYear();
                             }
                             $scope.mesActual.preDolar=3.50;
                             $scope.mesActual.mes=$scope.mes1+'-'+fecha.getFullYear();
                             $scope.mesActuales.push($scope.mesActual);
                             $scope.mesActual={};
                         }
                   } });

                }   
                $scope.actualizarPreDolar=function(index,row){
                    $scope.mesActuales.splice(index,1,row);
                } 
                $scope.cretatePredolar=function(){
                    crudService.create($scope.mesActuales, 'PreDolar').then(function (data) {
                          
                            if (data['estado'] == true) {
                                alert('grabado correctamente');
                                $location.path('/materials');

                            } else {
                                $scope.errors = data;

                            }
                        });
                }  
                $scope.actualizarPredolar=function(row){
                    crudService.update(row, 'updatePreDolar').then(function (data) {
                          
                            if (data['estado'] == true) {
                                alert('grabado correctamente');
                                
                            } else {
                                $scope.errors = data;

                            }
                        });
                }         
                $scope.restarFechas=function(f1,f2){
                       var aFecha1 = f1.split('/'); 
                       var aFecha2 = f2.split('/'); 
                       var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]); 
                       var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]); 
                       var dif = fFecha2 - fFecha1;
                       var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
                       return dias;
                }
                $scope.destroyMaterial = function(){
                    crudService.destroy($scope.material,'materials').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.material = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }
            }]);
})();
